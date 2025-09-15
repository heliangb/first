# PHP 代码规则模板 (PHP Code Rules Template)

## 概述 (Overview)

本文档定义了PHP项目的编码标准和最佳实践，基于PSR标准并结合现代PHP开发的最佳实践。

## PSR 标准遵循 (PSR Standards Compliance)

### 必须遵循的PSR标准
- **PSR-1**: 基本编码标准
- **PSR-2**: 编码风格指南 (已被PSR-12取代)
- **PSR-4**: 自动加载标准
- **PSR-12**: 扩展编码风格指南
- **PSR-7**: HTTP消息接口
- **PSR-11**: 容器接口
- **PSR-15**: HTTP服务器请求处理器

## 命名规范 (Naming Conventions)

### 变量 (Variables)
```php
// ✅ 正确 - camelCase
$userName = 'john_doe';
$totalAmount = 1500.50;
$isActive = true;

// ❌ 错误
$user_name = 'john_doe';        // snake_case
$UserName = 'john_doe';         // PascalCase
$username = 'john_doe';         // 不够描述性
```

### 函数和方法 (Functions and Methods)
```php
// ✅ 正确 - camelCase，动词开头
public function getUserData(): array
{
    return $this->userData;
}

public function calculateTotalAmount(): float
{
    return $this->items->sum('price');
}

// ❌ 错误
public function user_data() { }          // snake_case
public function UserData() { }           // PascalCase
public function data() { }               // 不够描述性
```

### 类 (Classes)
```php
// ✅ 正确 - PascalCase
class UserManager
{
    // ...
}

class DatabaseConnection
{
    // ...
}

// ❌ 错误
class userManager { }           // camelCase
class user_manager { }          // snake_case
```

### 接口和特性 (Interfaces and Traits)
```php
// ✅ 正确 - PascalCase + 后缀
interface UserRepositoryInterface
{
    // ...
}

trait LoggableTrait
{
    // ...
}

// ✅ 也可接受 - 不带后缀（Laravel风格）
interface UserRepository
{
    // ...
}

trait Loggable
{
    // ...
}
```

### 常量 (Constants)
```php
// ✅ 正确 - UPPER_SNAKE_CASE
class Config
{
    public const MAX_RETRY_COUNT = 3;
    public const API_BASE_URL = 'https://api.example.com';
    public const DEFAULT_TIMEOUT = 30;
}
```

### 命名空间 (Namespaces)
```php
// ✅ 正确 - PascalCase
namespace App\Services\User;
namespace App\Http\Controllers\Api\V1;
namespace Domain\User\Repositories;
```

## 代码格式化 (Code Formatting)

### PHP标签 (PHP Tags)
```php
<?php
// ✅ 正确 - 只有PHP代码的文件不需要关闭标签

<?php
// ❌ 错误 - 纯PHP文件不要使用关闭标签
?>
```

### 严格类型声明 (Strict Types)
```php
<?php

declare(strict_types=1);

namespace App\Services;

// 所有PHP文件都应该声明严格类型
```

### 缩进和空格 (Indentation and Spacing)
```php
<?php

declare(strict_types=1);

class UserService
{
    private UserRepository $userRepository;
    
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    
    public function createUser(array $userData): User
    {
        if (empty($userData['email'])) {
            throw new InvalidArgumentException('Email is required');
        }
        
        $user = new User(
            name: $userData['name'],
            email: $userData['email'],
            age: $userData['age'] ?? null
        );
        
        return $this->userRepository->save($user);
    }
}
```

### 数组语法 (Array Syntax)
```php
// ✅ 正确 - 短数组语法
$users = [
    'john' => [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'roles' => ['admin', 'user'],
    ],
    'jane' => [
        'name' => 'Jane Smith',
        'email' => 'jane@example.com',
        'roles' => ['user'],
    ],
];

// ❌ 错误 - 长数组语法
$users = array(
    'john' => array(
        'name' => 'John Doe',
        'email' => 'john@example.com',
    ),
);
```

## 类型声明 (Type Declarations)

### 参数类型 (Parameter Types)
```php
// ✅ 正确 - 明确的类型声明
public function processUser(
    User $user,
    string $action,
    array $options = [],
    ?DateTime $scheduledAt = null
): bool {
    // ...
}

// ✅ 联合类型 (PHP 8.0+)
public function handleId(int|string $id): User
{
    // ...
}
```

### 返回类型 (Return Types)
```php
// ✅ 正确 - 明确的返回类型
public function getUsers(): array
{
    return $this->users;
}

public function findUser(int $id): ?User
{
    return $this->users[$id] ?? null;
}

public function deleteUser(int $id): void
{
    unset($this->users[$id]);
}
```

### 属性类型 (Property Types)
```php
// ✅ 正确 - PHP 7.4+ 属性类型
class User
{
    public string $name;
    public string $email;
    public ?DateTime $birthDate = null;
    public array $roles = [];
    
    // ✅ PHP 8.0+ 构造器属性提升
    public function __construct(
        public string $name,
        public string $email,
        public ?DateTime $birthDate = null,
        public array $roles = []
    ) {
    }
}
```

## 面向对象编程 (Object-Oriented Programming)

### 单一职责原则 (Single Responsibility Principle)
```php
// ✅ 正确 - 每个类只有一个职责
class UserValidator
{
    public function validate(array $userData): array
    {
        $errors = [];
        
        if (empty($userData['email'])) {
            $errors[] = 'Email is required';
        }
        
        if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email format is invalid';
        }
        
        return $errors;
    }
}

class UserRepository
{
    public function save(User $user): User
    {
        // 保存用户逻辑
    }
    
    public function findById(int $id): ?User
    {
        // 查找用户逻辑
    }
}
```

### 依赖注入 (Dependency Injection)
```php
// ✅ 正确 - 构造器注入
class UserService
{
    public function __construct(
        private UserRepository $userRepository,
        private UserValidator $userValidator,
        private EventDispatcher $eventDispatcher
    ) {
    }
    
    public function createUser(array $userData): User
    {
        $errors = $this->userValidator->validate($userData);
        
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        
        $user = new User($userData);
        $savedUser = $this->userRepository->save($user);
        
        $this->eventDispatcher->dispatch(new UserCreated($savedUser));
        
        return $savedUser;
    }
}
```

### 接口定义 (Interface Definition)
```php
// ✅ 正确 - 定义合约
interface UserRepositoryInterface
{
    public function save(User $user): User;
    public function findById(int $id): ?User;
    public function findByEmail(string $email): ?User;
    public function delete(User $user): void;
}

interface CacheInterface
{
    public function get(string $key): mixed;
    public function set(string $key, mixed $value, int $ttl = 3600): bool;
    public function delete(string $key): bool;
    public function clear(): bool;
}
```

## 错误处理 (Error Handling)

### 异常处理 (Exception Handling)
```php
// ✅ 正确 - 自定义异常
class UserNotFoundException extends Exception
{
    public function __construct(int $userId)
    {
        parent::__construct("User with ID {$userId} not found");
    }
}

class ValidationException extends Exception
{
    public function __construct(
        private array $errors,
        string $message = 'Validation failed'
    ) {
        parent::__construct($message);
    }
    
    public function getErrors(): array
    {
        return $this->errors;
    }
}

// ✅ 正确 - 异常使用
public function getUser(int $id): User
{
    $user = $this->userRepository->findById($id);
    
    if ($user === null) {
        throw new UserNotFoundException($id);
    }
    
    return $user;
}
```

### Try-Catch 使用 (Try-Catch Usage)
```php
// ✅ 正确 - 具体异常捕获
public function processPayment(Payment $payment): PaymentResult
{
    try {
        $this->paymentGateway->charge($payment);
        $this->emailService->sendConfirmation($payment->getUser());
        
        return new PaymentResult(true, 'Payment processed successfully');
    } catch (PaymentGatewayException $e) {
        $this->logger->error('Payment gateway error', [
            'payment_id' => $payment->getId(),
            'error' => $e->getMessage(),
        ]);
        
        return new PaymentResult(false, 'Payment processing failed');
    } catch (EmailServiceException $e) {
        $this->logger->warning('Email notification failed', [
            'payment_id' => $payment->getId(),
            'error' => $e->getMessage(),
        ]);
        
        // 支付成功但邮件发送失败
        return new PaymentResult(true, 'Payment processed, but confirmation email failed');
    }
}
```

## 安全编程 (Security Programming)

### 输入验证 (Input Validation)
```php
// ✅ 正确 - 输入验证和清理
class UserController
{
    public function createUser(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'age' => 'nullable|integer|min:13|max:120',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }
        
        $userData = $validator->validated();
        $userData['password'] = Hash::make($userData['password']);
        
        $user = $this->userService->createUser($userData);
        
        return response()->json($user, 201);
    }
}
```

### SQL注入防护 (SQL Injection Prevention)
```php
// ✅ 正确 - 使用预处理语句
class UserRepository
{
    public function findByEmail(string $email): ?User
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM users WHERE email = ? AND deleted_at IS NULL'
        );
        
        $stmt->execute([$email]);
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $userData ? User::fromArray($userData) : null;
    }
    
    // ✅ 使用ORM (更推荐)
    public function findByEmailORM(string $email): ?User
    {
        return User::where('email', $email)
                  ->whereNull('deleted_at')
                  ->first();
    }
}
```

### XSS防护 (XSS Prevention)
```php
// ✅ 正确 - 输出转义
class BlogController
{
    public function show(int $id): View
    {
        $post = $this->blogRepository->findById($id);
        
        return view('blog.show', [
            'title' => htmlspecialchars($post->getTitle(), ENT_QUOTES, 'UTF-8'),
            'content' => $this->markdownParser->toHtml($post->getContent()), // 假设已处理
            'author' => htmlspecialchars($post->getAuthor()->getName(), ENT_QUOTES, 'UTF-8'),
        ]);
    }
}

// ✅ 在模板中使用转义 (Blade示例)
{{-- resources/views/blog/show.blade.php --}}
<h1>{{ $post->title }}</h1> {{-- 自动转义 --}}
<div class="content">{!! $post->content !!}</div> {{-- 已清理的HTML --}}
<p>By: {{ $post->author->name }}</p>
```

## 数据库操作 (Database Operations)

### 查询构建 (Query Building)
```php
// ✅ 正确 - 使用查询构建器
class ProductRepository
{
    public function findActiveProducts(int $categoryId, int $limit = 10): Collection
    {
        return DB::table('products')
            ->where('category_id', $categoryId)
            ->where('is_active', true)
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
    
    public function getProductStats(int $categoryId): array
    {
        return DB::table('products')
            ->where('category_id', $categoryId)
            ->selectRaw('
                COUNT(*) as total_count,
                AVG(price) as average_price,
                MIN(price) as min_price,
                MAX(price) as max_price
            ')
            ->first();
    }
}
```

### 事务处理 (Transaction Handling)
```php
// ✅ 正确 - 事务使用
class OrderService
{
    public function createOrder(array $orderData): Order
    {
        return DB::transaction(function () use ($orderData) {
            $order = Order::create([
                'user_id' => $orderData['user_id'],
                'total_amount' => $orderData['total_amount'],
                'status' => 'pending',
            ]);
            
            foreach ($orderData['items'] as $itemData) {
                $order->items()->create($itemData);
                
                // 更新库存
                $product = Product::findOrFail($itemData['product_id']);
                $product->decrement('stock_quantity', $itemData['quantity']);
            }
            
            // 发送通知
            $this->notificationService->sendOrderConfirmation($order);
            
            return $order;
        });
    }
}
```

## 测试规范 (Testing Standards)

### 单元测试 (Unit Tests)
```php
// ✅ 正确 - PHPUnit测试
class UserServiceTest extends TestCase
{
    private UserService $userService;
    private MockObject $userRepository;
    private MockObject $userValidator;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->userValidator = $this->createMock(UserValidator::class);
        $this->userService = new UserService(
            $this->userRepository,
            $this->userValidator
        );
    }
    
    public function test_should_create_user_when_valid_data_provided(): void
    {
        // Arrange
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ];
        
        $expectedUser = new User($userData);
        
        $this->userValidator
            ->expects($this->once())
            ->method('validate')
            ->with($userData)
            ->willReturn([]);
            
        $this->userRepository
            ->expects($this->once())
            ->method('save')
            ->willReturn($expectedUser);
        
        // Act
        $result = $this->userService->createUser($userData);
        
        // Assert
        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals('John Doe', $result->getName());
        $this->assertEquals('john@example.com', $result->getEmail());
    }
    
    public function test_should_throw_exception_when_validation_fails(): void
    {
        // Arrange
        $userData = ['email' => 'invalid-email'];
        $validationErrors = ['Email format is invalid'];
        
        $this->userValidator
            ->expects($this->once())
            ->method('validate')
            ->with($userData)
            ->willReturn($validationErrors);
        
        // Assert & Act
        $this->expectException(ValidationException::class);
        $this->userService->createUser($userData);
    }
}
```

### 集成测试 (Integration Tests)
```php
// ✅ 正确 - Laravel集成测试
class UserApiTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_should_create_user_via_api(): void
    {
        // Arrange
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];
        
        // Act
        $response = $this->postJson('/api/users', $userData);
        
        // Assert
        $response->assertStatus(201)
                ->assertJsonStructure([
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at',
                ]);
        
        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);
    }
}
```

## 性能优化 (Performance Optimization)

### 数据库优化 (Database Optimization)
```php
// ✅ 正确 - 避免N+1查询
class PostController
{
    public function index(): JsonResponse
    {
        $posts = Post::with(['author', 'category', 'tags'])
                    ->where('is_published', true)
                    ->orderBy('published_at', 'desc')
                    ->paginate(20);
        
        return response()->json($posts);
    }
}

// ✅ 正确 - 使用索引
class UserRepository
{
    public function findActiveUsersByRole(string $role): Collection
    {
        // 确保在 users 表上有 (role, is_active, deleted_at) 复合索引
        return User::where('role', $role)
                  ->where('is_active', true)
                  ->whereNull('deleted_at')
                  ->get();
    }
}
```

### 缓存策略 (Caching Strategy)
```php
// ✅ 正确 - 缓存使用
class ProductService
{
    public function getPopularProducts(int $limit = 10): Collection
    {
        $cacheKey = "popular_products_{$limit}";
        
        return Cache::remember($cacheKey, 3600, function () use ($limit) {
            return Product::where('is_active', true)
                         ->orderBy('view_count', 'desc')
                         ->limit($limit)
                         ->get();
        });
    }
    
    public function updateProduct(Product $product, array $data): Product
    {
        $product->update($data);
        
        // 清除相关缓存
        Cache::tags(['products', "product_{$product->id}"])->flush();
        
        return $product;
    }
}
```

## 文档规范 (Documentation Standards)

### PHPDoc 注释 (PHPDoc Comments)
```php
/**
 * 用户服务类，处理用户相关的业务逻辑
 *
 * @package App\Services
 * @author John Doe <john@example.com>
 * @since 1.0.0
 */
class UserService
{
    /**
     * 创建新用户
     *
     * @param array<string, mixed> $userData 用户数据数组
     * @return User 创建的用户对象
     * 
     * @throws ValidationException 当用户数据验证失败时
     * @throws UserAlreadyExistsException 当用户已存在时
     * 
     * @example
     * $userData = [
     *     'name' => 'John Doe',
     *     'email' => 'john@example.com',
     *     'password' => 'secure_password'
     * ];
     * $user = $userService->createUser($userData);
     */
    public function createUser(array $userData): User
    {
        // 实现逻辑
    }
    
    /**
     * 根据ID查找用户
     *
     * @param int $id 用户ID
     * @return User|null 找到的用户对象，未找到则返回null
     */
    public function findUserById(int $id): ?User
    {
        return $this->userRepository->findById($id);
    }
}
```

## 工具配置建议 (Tool Configuration Recommendations)

### Composer 配置 (Composer Configuration)
```json
{
    "require": {
        "php": "^8.1",
        "ext-json": "*",
        "ext-pdo": "*"
    },
    "require-dev": {
        "phpunit/phpunit": "^10.0",
        "friendsofphp/php-cs-fixer": "^3.0",
        "phpstan/phpstan": "^1.0",
        "psalm/psalm": "^5.0",
        "phpmd/phpmd": "^2.13"
    },
    "autoload": {
        "psr-4": {
            "App\\": "src/",
            "Domain\\": "domain/"
        }
    },
    "autoload-dev": {
        "psr-4": {
            "Tests\\": "tests/"
        }
    },
    "scripts": {
        "test": "phpunit",
        "test:coverage": "phpunit --coverage-html coverage",
        "cs:check": "php-cs-fixer fix --dry-run --diff",
        "cs:fix": "php-cs-fixer fix",
        "analyse": "phpstan analyse",
        "psalm": "psalm",
        "phpmd": "phpmd src text cleancode,codesize,controversial,design,naming,unusedcode"
    }
}
```

## 项目结构建议 (Project Structure Recommendations)

```
project/
├── src/
│   ├── Controllers/
│   ├── Services/
│   ├── Repositories/
│   ├── Models/
│   ├── Exceptions/
│   ├── Validators/
│   └── Utils/
├── tests/
│   ├── Unit/
│   ├── Integration/
│   └── Feature/
├── config/
├── public/
├── resources/
│   ├── views/
│   └── lang/
├── database/
│   ├── migrations/
│   └── seeders/
├── .php-cs-fixer.php
├── phpstan.neon
├── phpunit.xml
└── composer.json
```

---

## 总结 (Summary)

遵循这些规范将有助于：

1. **提高代码质量**: 通过一致的编码标准
2. **增强可维护性**: 通过清晰的结构和命名
3. **提升团队协作**: 通过统一的代码风格
4. **减少错误**: 通过严格的类型检查和测试
5. **优化性能**: 通过最佳实践和性能指导

定期回顾和更新这些规范，确保它们与项目需求和PHP生态系统的发展保持同步。