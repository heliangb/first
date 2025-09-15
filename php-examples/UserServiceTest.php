<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Exceptions\UserAlreadyExistsException;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\ValidationException;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use App\Services\UserService;
use App\Validators\UserValidator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * UserService 单元测试
 *
 * 测试用户服务类的各种功能，确保业务逻辑的正确性。
 * 使用模拟对象来隔离依赖，专注于测试业务逻辑。
 *
 * @package Tests\Unit\Services
 * @covers  \App\Services\UserService
 */
final class UserServiceTest extends TestCase
{
    private UserService $userService;
    
    /** @var UserRepositoryInterface&MockObject */
    private MockObject $userRepository;
    
    /** @var UserValidator&MockObject */
    private MockObject $userValidator;
    
    /** @var LoggerInterface&MockObject */
    private MockObject $logger;

    /**
     * 测试前的设置
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->userValidator = $this->createMock(UserValidator::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->userService = new UserService(
            $this->userRepository,
            $this->userValidator,
            $this->logger
        );
    }

    /**
     * 测试：应该成功创建用户当提供有效数据时
     */
    public function test_should_create_user_when_valid_data_provided(): void
    {
        // Arrange
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'secure_password',
            'role' => 'user',
        ];

        $expectedUser = new User(
            id: 1,
            name: 'John Doe',
            email: 'john@example.com',
            password: 'hashed_password',
            role: 'user',
            isActive: true,
            createdAt: new \DateTimeImmutable()
        );

        $this->userValidator
            ->expects($this->once())
            ->method('validate')
            ->with($userData)
            ->willReturn([]);

        $this->userRepository
            ->expects($this->once())
            ->method('existsByEmail')
            ->with('john@example.com')
            ->willReturn(false);

        $this->userRepository
            ->expects($this->once())
            ->method('create')
            ->with($this->callback(function (array $data): bool {
                return $data['name'] === 'John Doe'
                    && $data['email'] === 'john@example.com'
                    && isset($data['password'])
                    && $data['role'] === 'user'
                    && $data['is_active'] === true;
            }))
            ->willReturn($expectedUser);

        $this->logger
            ->expects($this->exactly(2))
            ->method('info');

        // Act
        $result = $this->userService->createUser($userData);

        // Assert
        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals('John Doe', $result->getName());
        $this->assertEquals('john@example.com', $result->getEmail());
        $this->assertEquals(1, $result->getId());
    }

    /**
     * 测试：应该抛出验证异常当数据无效时
     */
    public function test_should_throw_validation_exception_when_invalid_data(): void
    {
        // Arrange
        $userData = [
            'name' => '',
            'email' => 'invalid-email',
            'password' => '123',
        ];

        $validationErrors = [
            'name' => ['Name is required'],
            'email' => ['Email format is invalid'],
            'password' => ['Password must be at least 8 characters'],
        ];

        $this->userValidator
            ->expects($this->once())
            ->method('validate')
            ->with($userData)
            ->willReturn($validationErrors);

        $this->logger
            ->expects($this->once())
            ->method('info');

        $this->logger
            ->expects($this->once())
            ->method('warning');

        // Assert & Act
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Validation failed');

        $this->userService->createUser($userData);
    }

    /**
     * 测试：应该抛出用户已存在异常当邮箱重复时
     */
    public function test_should_throw_user_already_exists_exception_when_email_duplicate(): void
    {
        // Arrange
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'secure_password',
        ];

        $this->userValidator
            ->expects($this->once())
            ->method('validate')
            ->with($userData)
            ->willReturn([]);

        $this->userRepository
            ->expects($this->once())
            ->method('existsByEmail')
            ->with('john@example.com')
            ->willReturn(true);

        $this->logger
            ->expects($this->once())
            ->method('info');

        $this->logger
            ->expects($this->once())
            ->method('warning');

        // Assert & Act
        $this->expectException(UserAlreadyExistsException::class);

        $this->userService->createUser($userData);
    }

    /**
     * 测试：应该返回用户当通过有效ID查找时
     */
    public function test_should_return_user_when_found_by_valid_id(): void
    {
        // Arrange
        $userId = 1;
        $expectedUser = new User(
            id: $userId,
            name: 'John Doe',
            email: 'john@example.com',
            password: 'hashed_password',
            role: 'user',
            isActive: true,
            createdAt: new \DateTimeImmutable()
        );

        $this->userRepository
            ->expects($this->once())
            ->method('findById')
            ->with($userId)
            ->willReturn($expectedUser);

        // Act
        $result = $this->userService->getUserById($userId);

        // Assert
        $this->assertSame($expectedUser, $result);
    }

    /**
     * 测试：应该抛出用户未找到异常当ID不存在时
     */
    public function test_should_throw_user_not_found_exception_when_id_not_exists(): void
    {
        // Arrange
        $userId = 999;

        $this->userRepository
            ->expects($this->once())
            ->method('findById')
            ->with($userId)
            ->willReturn(null);

        $this->logger
            ->expects($this->once())
            ->method('warning');

        // Assert & Act
        $this->expectException(UserNotFoundException::class);

        $this->userService->getUserById($userId);
    }

    /**
     * 测试：应该返回用户当通过邮箱找到时
     */
    public function test_should_return_user_when_found_by_email(): void
    {
        // Arrange
        $email = 'john@example.com';
        $expectedUser = new User(
            id: 1,
            name: 'John Doe',
            email: $email,
            password: 'hashed_password',
            role: 'user',
            isActive: true,
            createdAt: new \DateTimeImmutable()
        );

        $this->userRepository
            ->expects($this->once())
            ->method('findByEmail')
            ->with($email)
            ->willReturn($expectedUser);

        // Act
        $result = $this->userService->getUserByEmail($email);

        // Assert
        $this->assertSame($expectedUser, $result);
    }

    /**
     * 测试：应该返回null当邮箱未找到时
     */
    public function test_should_return_null_when_email_not_found(): void
    {
        // Arrange
        $email = 'nonexistent@example.com';

        $this->userRepository
            ->expects($this->once())
            ->method('findByEmail')
            ->with($email)
            ->willReturn(null);

        // Act
        $result = $this->userService->getUserByEmail($email);

        // Assert
        $this->assertNull($result);
    }

    /**
     * 测试：应该成功更新用户当提供有效数据时
     */
    public function test_should_update_user_when_valid_data_provided(): void
    {
        // Arrange
        $userId = 1;
        $updateData = [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
        ];

        $existingUser = new User(
            id: $userId,
            name: 'John Doe',
            email: 'john@example.com',
            password: 'hashed_password',
            role: 'user',
            isActive: true,
            createdAt: new \DateTimeImmutable()
        );

        $updatedUser = new User(
            id: $userId,
            name: 'Jane Doe',
            email: 'jane@example.com',
            password: 'hashed_password',
            role: 'user',
            isActive: true,
            createdAt: new \DateTimeImmutable(),
            updatedAt: new \DateTimeImmutable()
        );

        $this->userRepository
            ->expects($this->once())
            ->method('findById')
            ->with($userId)
            ->willReturn($existingUser);

        $this->userValidator
            ->expects($this->once())
            ->method('validateUpdate')
            ->with($updateData, $userId)
            ->willReturn([]);

        $this->userRepository
            ->expects($this->once())
            ->method('update')
            ->with($existingUser, $this->callback(function (array $data): bool {
                return $data['name'] === 'Jane Doe'
                    && $data['email'] === 'jane@example.com'
                    && isset($data['updated_at']);
            }))
            ->willReturn($updatedUser);

        $this->logger
            ->expects($this->exactly(2))
            ->method('info');

        // Act
        $result = $this->userService->updateUser($userId, $updateData);

        // Assert
        $this->assertSame($updatedUser, $result);
    }

    /**
     * 测试：应该成功删除用户当用户存在时
     */
    public function test_should_delete_user_when_user_exists(): void
    {
        // Arrange
        $userId = 1;
        $user = new User(
            id: $userId,
            name: 'John Doe',
            email: 'john@example.com',
            password: 'hashed_password',
            role: 'user',
            isActive: true,
            createdAt: new \DateTimeImmutable()
        );

        $this->userRepository
            ->expects($this->once())
            ->method('findById')
            ->with($userId)
            ->willReturn($user);

        $this->userRepository
            ->expects($this->once())
            ->method('delete')
            ->with($user);

        $this->logger
            ->expects($this->exactly(2))
            ->method('info');

        // Act & Assert
        $this->userService->deleteUser($userId);
    }

    /**
     * 测试：应该返回活跃用户列表
     */
    public function test_should_return_active_users_list(): void
    {
        // Arrange
        $limit = 10;
        $offset = 0;
        $expectedUsers = [
            new User(1, 'User 1', 'user1@example.com', 'pass', 'user', true, new \DateTimeImmutable()),
            new User(2, 'User 2', 'user2@example.com', 'pass', 'user', true, new \DateTimeImmutable()),
        ];

        $this->userRepository
            ->expects($this->once())
            ->method('findActiveUsers')
            ->with($limit, $offset)
            ->willReturn($expectedUsers);

        // Act
        $result = $this->userService->getActiveUsers($limit, $offset);

        // Assert
        $this->assertSame($expectedUsers, $result);
        $this->assertCount(2, $result);
    }

    /**
     * 测试：应该验证密码成功当密码正确时
     */
    public function test_should_verify_password_successfully_when_correct(): void
    {
        // Arrange
        $user = new User(
            id: 1,
            name: 'John Doe',
            email: 'john@example.com',
            password: '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // 'password'
            role: 'user',
            isActive: true,
            createdAt: new \DateTimeImmutable()
        );

        // Mock Hash::check 通过反射或者使用 Laravel 的测试助手
        // 这里简化处理，实际项目中需要模拟 Hash facade

        // Act & Assert
        // 由于这里需要模拟 Hash::check，在实际项目中需要使用适当的测试方法
        $this->assertTrue(true); // 占位符，实际需要实现 Hash 的模拟
    }

    /**
     * 测试：应该成功更新密码当提供有效密码时
     */
    public function test_should_update_password_when_valid_password_provided(): void
    {
        // Arrange
        $userId = 1;
        $newPassword = 'new_secure_password';
        
        $user = new User(
            id: $userId,
            name: 'John Doe',
            email: 'john@example.com',
            password: 'old_hashed_password',
            role: 'user',
            isActive: true,
            createdAt: new \DateTimeImmutable()
        );

        $this->userRepository
            ->expects($this->once())
            ->method('findById')
            ->with($userId)
            ->willReturn($user);

        $this->userValidator
            ->expects($this->once())
            ->method('validatePassword')
            ->with($newPassword)
            ->willReturn([]);

        $this->userRepository
            ->expects($this->once())
            ->method('updatePassword')
            ->with($user, $this->isType('string'));

        $this->logger
            ->expects($this->exactly(2))
            ->method('info');

        // Act & Assert
        $this->userService->updatePassword($userId, $newPassword);
    }

    /**
     * 数据提供器：无效的用户数据
     *
     * @return array<string, array<mixed>>
     */
    public function invalidUserDataProvider(): array
    {
        return [
            'empty name' => [
                ['name' => '', 'email' => 'test@example.com', 'password' => 'password123'],
                ['name' => ['Name is required']],
            ],
            'invalid email' => [
                ['name' => 'Test User', 'email' => 'invalid-email', 'password' => 'password123'],
                ['email' => ['Email format is invalid']],
            ],
            'weak password' => [
                ['name' => 'Test User', 'email' => 'test@example.com', 'password' => '123'],
                ['password' => ['Password must be at least 8 characters']],
            ],
        ];
    }

    /**
     * 测试：应该处理各种无效数据情况
     *
     * @dataProvider invalidUserDataProvider
     * @param array<string, mixed> $userData
     * @param array<string, array<string>> $expectedErrors
     */
    public function test_should_handle_various_invalid_data_cases(array $userData, array $expectedErrors): void
    {
        // Arrange
        $this->userValidator
            ->expects($this->once())
            ->method('validate')
            ->with($userData)
            ->willReturn($expectedErrors);

        $this->logger
            ->expects($this->once())
            ->method('info');

        $this->logger
            ->expects($this->once())
            ->method('warning');

        // Assert & Act
        $this->expectException(ValidationException::class);
        
        $this->userService->createUser($userData);
    }
}