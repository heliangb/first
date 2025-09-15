<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\UserAlreadyExistsException;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\ValidationException;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use App\Validators\UserValidator;
use Illuminate\Support\Facades\Hash;
use Psr\Log\LoggerInterface;

/**
 * 用户服务类
 * 
 * 处理用户相关的业务逻辑，包括用户创建、更新、删除等操作。
 * 遵循单一职责原则，只负责用户业务逻辑的处理。
 *
 * @package App\Services
 * @author  开发团队 <dev@example.com>
 * @since   1.0.0
 */
final class UserService
{
    /**
     * 构造函数
     *
     * @param UserRepositoryInterface $userRepository 用户仓库接口
     * @param UserValidator          $userValidator  用户验证器
     * @param LoggerInterface        $logger         日志记录器
     */
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private UserValidator $userValidator,
        private LoggerInterface $logger
    ) {
    }

    /**
     * 创建新用户
     *
     * @param array<string, mixed> $userData 用户数据
     * @return User 创建的用户对象
     * 
     * @throws ValidationException       当用户数据验证失败时
     * @throws UserAlreadyExistsException 当用户已存在时
     * 
     * @example
     * $userData = [
     *     'name' => 'John Doe',
     *     'email' => 'john@example.com',
     *     'password' => 'secure_password',
     *     'role' => 'user'
     * ];
     * $user = $userService->createUser($userData);
     */
    public function createUser(array $userData): User
    {
        $this->logger->info('开始创建用户', ['email' => $userData['email'] ?? 'unknown']);

        // 验证输入数据
        $errors = $this->userValidator->validate($userData);
        if (!empty($errors)) {
            $this->logger->warning('用户数据验证失败', [
                'errors' => $errors,
                'data' => $this->sanitizeUserData($userData),
            ]);
            
            throw new ValidationException($errors);
        }

        // 检查用户是否已存在
        if ($this->userRepository->existsByEmail($userData['email'])) {
            $this->logger->warning('尝试创建已存在的用户', ['email' => $userData['email']]);
            
            throw new UserAlreadyExistsException($userData['email']);
        }

        // 准备用户数据
        $preparedData = $this->prepareUserData($userData);

        // 创建用户
        $user = $this->userRepository->create($preparedData);

        $this->logger->info('用户创建成功', [
            'user_id' => $user->getId(),
            'email' => $user->getEmail(),
        ]);

        return $user;
    }

    /**
     * 根据ID获取用户
     *
     * @param int $userId 用户ID
     * @return User 用户对象
     * 
     * @throws UserNotFoundException 当用户不存在时
     */
    public function getUserById(int $userId): User
    {
        $user = $this->userRepository->findById($userId);

        if ($user === null) {
            $this->logger->warning('用户未找到', ['user_id' => $userId]);
            
            throw new UserNotFoundException($userId);
        }

        return $user;
    }

    /**
     * 根据邮箱获取用户
     *
     * @param string $email 用户邮箱
     * @return User|null 用户对象或null
     */
    public function getUserByEmail(string $email): ?User
    {
        return $this->userRepository->findByEmail($email);
    }

    /**
     * 更新用户信息
     *
     * @param int                  $userId   用户ID
     * @param array<string, mixed> $userData 更新数据
     * @return User 更新后的用户对象
     * 
     * @throws UserNotFoundException 当用户不存在时
     * @throws ValidationException   当数据验证失败时
     */
    public function updateUser(int $userId, array $userData): User
    {
        $this->logger->info('开始更新用户', ['user_id' => $userId]);

        $user = $this->getUserById($userId);

        // 验证更新数据
        $errors = $this->userValidator->validateUpdate($userData, $userId);
        if (!empty($errors)) {
            $this->logger->warning('用户更新数据验证失败', [
                'user_id' => $userId,
                'errors' => $errors,
            ]);
            
            throw new ValidationException($errors);
        }

        // 准备更新数据
        $preparedData = $this->prepareUpdateData($userData);

        // 更新用户
        $updatedUser = $this->userRepository->update($user, $preparedData);

        $this->logger->info('用户更新成功', ['user_id' => $updatedUser->getId()]);

        return $updatedUser;
    }

    /**
     * 删除用户
     *
     * @param int $userId 用户ID
     * @return void
     * 
     * @throws UserNotFoundException 当用户不存在时
     */
    public function deleteUser(int $userId): void
    {
        $this->logger->info('开始删除用户', ['user_id' => $userId]);

        $user = $this->getUserById($userId);

        $this->userRepository->delete($user);

        $this->logger->info('用户删除成功', ['user_id' => $userId]);
    }

    /**
     * 获取活跃用户列表
     *
     * @param int $limit  限制数量
     * @param int $offset 偏移量
     * @return array<User> 用户列表
     */
    public function getActiveUsers(int $limit = 10, int $offset = 0): array
    {
        return $this->userRepository->findActiveUsers($limit, $offset);
    }

    /**
     * 验证用户密码
     *
     * @param User   $user     用户对象
     * @param string $password 密码
     * @return bool 验证结果
     */
    public function verifyPassword(User $user, string $password): bool
    {
        return Hash::check($password, $user->getPassword());
    }

    /**
     * 更新用户密码
     *
     * @param int    $userId      用户ID
     * @param string $newPassword 新密码
     * @return void
     * 
     * @throws UserNotFoundException 当用户不存在时
     * @throws ValidationException   当密码验证失败时
     */
    public function updatePassword(int $userId, string $newPassword): void
    {
        $this->logger->info('开始更新用户密码', ['user_id' => $userId]);

        $user = $this->getUserById($userId);

        // 验证密码强度
        $errors = $this->userValidator->validatePassword($newPassword);
        if (!empty($errors)) {
            $this->logger->warning('密码验证失败', ['user_id' => $userId]);
            
            throw new ValidationException($errors);
        }

        $hashedPassword = Hash::make($newPassword);
        
        $this->userRepository->updatePassword($user, $hashedPassword);

        $this->logger->info('用户密码更新成功', ['user_id' => $userId]);
    }

    /**
     * 准备用户创建数据
     *
     * @param array<string, mixed> $userData 原始用户数据
     * @return array<string, mixed> 准备好的数据
     */
    private function prepareUserData(array $userData): array
    {
        $preparedData = [
            'name' => trim($userData['name']),
            'email' => strtolower(trim($userData['email'])),
            'password' => Hash::make($userData['password']),
            'role' => $userData['role'] ?? 'user',
            'is_active' => $userData['is_active'] ?? true,
            'created_at' => new \DateTimeImmutable(),
        ];

        // 处理可选字段
        if (isset($userData['phone'])) {
            $preparedData['phone'] = trim($userData['phone']);
        }

        if (isset($userData['birth_date'])) {
            $preparedData['birth_date'] = new \DateTimeImmutable($userData['birth_date']);
        }

        return $preparedData;
    }

    /**
     * 准备用户更新数据
     *
     * @param array<string, mixed> $userData 更新数据
     * @return array<string, mixed> 准备好的数据
     */
    private function prepareUpdateData(array $userData): array
    {
        $preparedData = [];

        if (isset($userData['name'])) {
            $preparedData['name'] = trim($userData['name']);
        }

        if (isset($userData['email'])) {
            $preparedData['email'] = strtolower(trim($userData['email']));
        }

        if (isset($userData['phone'])) {
            $preparedData['phone'] = trim($userData['phone']);
        }

        if (isset($userData['is_active'])) {
            $preparedData['is_active'] = (bool) $userData['is_active'];
        }

        if (isset($userData['role'])) {
            $preparedData['role'] = $userData['role'];
        }

        $preparedData['updated_at'] = new \DateTimeImmutable();

        return $preparedData;
    }

    /**
     * 清理用户数据用于日志记录
     *
     * @param array<string, mixed> $userData 用户数据
     * @return array<string, mixed> 清理后的数据
     */
    private function sanitizeUserData(array $userData): array
    {
        $sanitized = $userData;
        
        // 移除敏感信息
        unset($sanitized['password'], $sanitized['password_confirmation']);
        
        return $sanitized;
    }
}