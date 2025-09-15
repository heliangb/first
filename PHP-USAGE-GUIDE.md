# PHP 代码规则使用指南

## 🚀 快速开始

### 1. 项目初始化

```bash
# 创建新的PHP项目
composer create-project --prefer-dist laravel/laravel your-project-name

# 或者克隆现有项目后安装依赖
composer install

# 复制环境配置文件
cp .env.example .env

# 生成应用密钥
php artisan key:generate
```

### 2. 安装开发工具

```bash
# 安装代码质量工具
composer require --dev friendsofphp/php-cs-fixer
composer require --dev phpstan/phpstan
composer require --dev vimeo/psalm
composer require --dev phpmd/phpmd
composer require --dev phpunit/phpunit

# Laravel 特定工具
composer require --dev nunomaduro/larastan
composer require --dev laravel/pint
```

### 3. 配置文件设置

将以下配置文件复制到项目根目录：

- `.php-cs-fixer.php` - PHP CS Fixer 配置
- `phpstan.neon` - PHPStan 配置  
- `psalm.xml` - Psalm 配置
- `phpunit.xml` - PHPUnit 配置

## 🛠️ 工具使用指南

### PHP CS Fixer (代码格式化)

```bash
# 检查代码风格问题
./vendor/bin/php-cs-fixer fix --dry-run --diff

# 自动修复代码风格
./vendor/bin/php-cs-fixer fix

# 检查特定文件
./vendor/bin/php-cs-fixer fix src/Services/UserService.php --dry-run --diff
```

### Laravel Pint (Laravel 官方格式化工具)

```bash
# 检查代码风格
./vendor/bin/pint --test

# 修复代码风格
./vendor/bin/pint

# 修复特定目录
./vendor/bin/pint app/Services
```

### PHPStan (静态分析)

```bash
# 运行静态分析
./vendor/bin/phpstan analyse

# 指定分析级别 (0-9)
./vendor/bin/phpstan analyse --level=8

# 生成基线文件（忽略现有错误）
./vendor/bin/phpstan analyse --generate-baseline

# 分析特定目录
./vendor/bin/phpstan analyse src/Services
```

### Psalm (静态分析)

```bash
# 运行 Psalm 分析
./vendor/bin/psalm

# 生成基线文件
./vendor/bin/psalm --set-baseline=psalm-baseline.xml

# 显示信息级别的问题
./vendor/bin/psalm --show-info=true

# 查找死代码
./vendor/bin/psalm --find-dead-code
```

### PHPMD (代码复杂度检测)

```bash
# 检查代码质量
./vendor/bin/phpmd src text cleancode,codesize,controversial,design,naming,unusedcode

# 生成HTML报告
./vendor/bin/phpmd src html cleancode,codesize,controversial,design,naming,unusedcode --reportfile phpmd.html

# 检查特定文件
./vendor/bin/phpmd src/Services/UserService.php text cleancode,codesize
```

### PHPUnit (单元测试)

```bash
# 运行所有测试
./vendor/bin/phpunit

# 运行特定测试套件
./vendor/bin/phpunit --testsuite=Unit

# 生成覆盖率报告
./vendor/bin/phpunit --coverage-html coverage

# 运行特定测试类
./vendor/bin/phpunit tests/Unit/Services/UserServiceTest.php

# 运行特定测试方法
./vendor/bin/phpunit --filter=test_should_create_user_when_valid_data_provided
```

## 📝 编码规范示例

### 类定义示例

```php
<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\ValidationException;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Psr\Log\LoggerInterface;

/**
 * 用户服务类
 * 
 * 处理用户相关的业务逻辑
 */
final class UserService
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private LoggerInterface $logger
    ) {
    }

    /**
     * 创建新用户
     *
     * @param array<string, mixed> $userData 用户数据
     * @return User 创建的用户
     * 
     * @throws ValidationException 当数据验证失败时
     */
    public function createUser(array $userData): User
    {
        // 实现逻辑
    }
}
```

### 测试类示例

```php
<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Services\UserService;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \App\Services\UserService
 */
final class UserServiceTest extends TestCase
{
    private UserService $userService;
    private MockObject $userRepository;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->userService = new UserService($this->userRepository);
    }

    public function test_should_create_user_when_valid_data_provided(): void
    {
        // Arrange
        $userData = ['name' => 'John', 'email' => 'john@example.com'];
        
        // Act
        $result = $this->userService->createUser($userData);
        
        // Assert
        $this->assertInstanceOf(User::class, $result);
    }
}
```

## 🔧 IDE 配置

### PhpStorm 配置

1. **代码风格设置**：
   - File → Settings → Editor → Code Style → PHP
   - 选择 "PSR-12" 预设

2. **PHP CS Fixer 集成**：
   - File → Settings → Tools → External Tools
   - 添加 PHP CS Fixer 工具配置

3. **PHPStan 集成**：
   - 安装 PHPStan 插件
   - 配置 phpstan.neon 路径

### VS Code 配置

安装推荐扩展：
```json
{
    "recommendations": [
        "bmewburn.vscode-intelephense-client",
        "junstyle.php-cs-fixer",
        "sanderronde.phpstan-vscode",
        "psalm.psalm-vscode-plugin"
    ]
}
```

配置文件 `.vscode/settings.json`：
```json
{
    "php.validate.executablePath": "/usr/bin/php",
    "php-cs-fixer.executablePath": "./vendor/bin/php-cs-fixer",
    "php-cs-fixer.onsave": true,
    "phpstan.enabled": true,
    "phpstan.configPath": "./phpstan.neon"
}
```

## 🚀 CI/CD 集成

### GitHub Actions 配置

创建 `.github/workflows/php.yml`：

```yaml
name: PHP Quality Assurance

on:
  push:
    branches: [ main, develop ]
  pull_request:
    branches: [ main ]

jobs:
  quality:
    runs-on: ubuntu-latest
    
    strategy:
      matrix:
        php-version: [8.1, 8.2, 8.3]
    
    steps:
    - uses: actions/checkout@v4
    
    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: ${{ matrix.php-version }}
        extensions: mbstring, xml, ctype, iconv, intl, pdo, pdo_mysql, dom, filter, gd, iconv, json, mbstring
        coverage: xdebug
    
    - name: Cache Composer packages
      id: composer-cache
      uses: actions/cache@v3
      with:
        path: vendor
        key: ${{ runner.os }}-php-${{ hashFiles('**/composer.lock') }}
        restore-keys: |
          ${{ runner.os }}-php-
    
    - name: Install dependencies
      run: composer install --prefer-dist --no-progress
    
    - name: Check code style
      run: ./vendor/bin/php-cs-fixer fix --dry-run --diff --verbose
    
    - name: Run PHPStan
      run: ./vendor/bin/phpstan analyse --memory-limit=2G
    
    - name: Run Psalm
      run: ./vendor/bin/psalm --output-format=github
    
    - name: Run PHPMD
      run: ./vendor/bin/phpmd src github cleancode,codesize,controversial,design,naming,unusedcode
    
    - name: Run tests
      run: ./vendor/bin/phpunit --coverage-clover=coverage.xml
    
    - name: Upload coverage to Codecov
      uses: codecov/codecov-action@v3
      with:
        file: ./coverage.xml
        flags: unittests
        name: codecov-umbrella
```

### GitLab CI 配置

创建 `.gitlab-ci.yml`：

```yaml
image: php:8.2-fpm

variables:
  COMPOSER_CACHE_DIR: /cache/composer

cache:
  paths:
    - /cache/composer/
    - vendor/

before_script:
  - apt-get update -qq && apt-get install -y -qq git curl libmcrypt-dev libjpeg-dev libpng-dev libfreetype6-dev libbz2-dev
  - curl -sS https://getcomposer.org/installer | php
  - php composer.phar install

stages:
  - test
  - quality

test:
  stage: test
  script:
    - ./vendor/bin/phpunit --coverage-text --colors=never

code-style:
  stage: quality
  script:
    - ./vendor/bin/php-cs-fixer fix --dry-run --diff --verbose

static-analysis:
  stage: quality
  script:
    - ./vendor/bin/phpstan analyse
    - ./vendor/bin/psalm
```

## 📊 代码质量指标

### 推荐的质量阈值

- **测试覆盖率**: ≥ 80%
- **圈复杂度**: ≤ 10
- **方法行数**: ≤ 50
- **类行数**: ≤ 300
- **方法参数**: ≤ 5
- **嵌套深度**: ≤ 4

### 质量检查脚本

创建 `quality-check.sh`：

```bash
#!/bin/bash

echo "🔍 开始代码质量检查..."

echo "📝 检查代码风格..."
./vendor/bin/php-cs-fixer fix --dry-run --diff || exit 1

echo "🔬 运行静态分析..."
./vendor/bin/phpstan analyse || exit 1

echo "🔍 运行 Psalm..."
./vendor/bin/psalm || exit 1

echo "📊 检查代码复杂度..."
./vendor/bin/phpmd src text cleancode,codesize,controversial,design,naming,unusedcode || exit 1

echo "🧪 运行测试..."
./vendor/bin/phpunit || exit 1

echo "✅ 所有质量检查通过！"
```

## 🔧 常见问题解决

### 1. 内存限制问题

```bash
# 临时增加内存限制
php -d memory_limit=2G vendor/bin/phpstan analyse

# 或者在配置文件中设置
echo 'memory_limit = 2G' >> php.ini
```

### 2. 缓存问题

```bash
# 清除 PHPStan 缓存
./vendor/bin/phpstan clear-result-cache

# 清除 Psalm 缓存
./vendor/bin/psalm --clear-cache

# 清除 PHP CS Fixer 缓存
rm .php-cs-fixer.cache
```

### 3. 配置文件找不到

```bash
# 检查配置文件路径
./vendor/bin/phpstan analyse --configuration=phpstan.neon
./vendor/bin/psalm --config=psalm.xml
```

## 📚 学习资源

### 官方文档
- [PSR 标准](https://www.php-fig.org/psr/)
- [PHP CS Fixer](https://cs.symfony.com/)
- [PHPStan](https://phpstan.org/)
- [Psalm](https://psalm.dev/)
- [PHPUnit](https://phpunit.de/)

### 最佳实践
- [PHP The Right Way](https://phptherightway.com/)
- [Clean Code PHP](https://github.com/jupeter/clean-code-php)
- [Laravel Best Practices](https://github.com/alexeymezenin/laravel-best-practices)

---

## 🤝 贡献指南

1. Fork 项目
2. 创建特性分支 (`git checkout -b feature/amazing-feature`)
3. 提交更改 (`git commit -m 'Add some amazing feature'`)
4. 推送到分支 (`git push origin feature/amazing-feature`)
5. 开启 Pull Request

## 📄 许可证

本项目基于 MIT 许可证 - 查看 [LICENSE](LICENSE) 文件了解详情。