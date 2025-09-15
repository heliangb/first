# 代码规则模板使用指南

## 快速开始

### 1. 选择合适的模板

根据您的项目类型选择相应的规则文件：

- **通用模板**: `code-rules-template.md` - 适用于所有项目的基础规则
- **JavaScript/TypeScript**: `rules-examples/javascript-rules.json`
- **Python**: `rules-examples/python-rules.yaml` 
- **Java**: `rules-examples/java-rules.xml`

### 2. 定制规则

1. 复制相应的模板文件到您的项目中
2. 根据项目需求修改规则
3. 删除不适用的规则部分
4. 添加项目特定的规则

### 3. 集成到开发工具

#### ESLint 配置 (JavaScript/TypeScript)
```json
{
  "extends": ["@typescript-eslint/recommended", "prettier"],
  "rules": {
    "no-unused-vars": "error",
    "no-console": "warn",
    "prefer-const": "error"
  }
}
```

#### Python 工具配置
```ini
# setup.cfg
[flake8]
max-line-length = 88
ignore = E203, W503

[isort]
profile = black
multi_line_output = 3
```

#### Java Checkstyle 配置
```xml
<?xml version="1.0"?>
<!DOCTYPE module PUBLIC
    "-//Checkstyle//DTD Checkstyle Configuration 1.3//EN"
    "https://checkstyle.org/dtds/configuration_1_3.dtd">
<module name="Checker">
    <module name="TreeWalker">
        <module name="Indentation"/>
        <module name="LineLength">
            <property name="max" value="120"/>
        </module>
    </module>
</module>
```

## 团队协作

### 1. 规则制定流程

1. **提议阶段**: 团队成员提出规则修改建议
2. **讨论阶段**: 团队讨论规则的必要性和可行性
3. **试运行**: 在小范围内试运行新规则
4. **正式采用**: 团队一致同意后正式采用

### 2. 规则执行

#### 自动化检查
```yaml
# .github/workflows/code-quality.yml
name: Code Quality
on: [push, pull_request]
jobs:
  lint:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - name: Setup Node
        uses: actions/setup-node@v2
        with:
          node-version: '16'
      - name: Install dependencies
        run: npm install
      - name: Run linting
        run: npm run lint
      - name: Run tests
        run: npm test
```

#### Pre-commit 钩子
```yaml
# .pre-commit-config.yaml
repos:
  - repo: https://github.com/pre-commit/pre-commit-hooks
    rev: v4.0.1
    hooks:
      - id: trailing-whitespace
      - id: end-of-file-fixer
      - id: check-yaml
  - repo: https://github.com/psf/black
    rev: 21.9b0
    hooks:
      - id: black
```

## 常见问题

### Q: 如何处理规则冲突？
A: 
1. 优先考虑项目特定需求
2. 团队讨论达成一致
3. 记录例外情况和原因
4. 定期回顾和调整

### Q: 如何确保团队遵循规则？
A: 
1. 使用自动化工具强制执行
2. 代码审查时检查规则遵循情况
3. 定期培训和分享
4. 将规则遵循情况纳入绩效考核

### Q: 规则太严格怎么办？
A: 
1. 分析规则的实际价值
2. 考虑规则的灵活性
3. 允许合理的例外情况
4. 逐步调整规则严格程度

## 最佳实践

### 1. 渐进式采用
- 从基础规则开始
- 逐步增加规则复杂度
- 给团队适应时间

### 2. 工具集成
- 配置 IDE 支持规则检查
- 使用格式化工具自动修复
- 集成到 CI/CD 流程

### 3. 文档维护
- 保持规则文档更新
- 记录规则变更历史
- 提供规则使用示例

### 4. 团队培训
- 定期组织规则培训
- 分享最佳实践案例
- 鼓励团队成员贡献改进建议

## 扩展资源

### 工具推荐
- **JavaScript/TypeScript**: ESLint, Prettier, Husky
- **Python**: Black, isort, flake8, pylint, bandit
- **Java**: Checkstyle, PMD, SpotBugs, Google Java Format
- **通用**: EditorConfig, pre-commit, SonarQube

### 参考文档
- [Google Style Guides](https://google.github.io/styleguide/)
- [Airbnb Style Guides](https://github.com/airbnb/javascript)
- [PEP 8 Style Guide](https://www.python.org/dev/peps/pep-0008/)
- [Oracle Java Code Conventions](https://www.oracle.com/java/technologies/javase/codeconventions-contents.html)

### 社区资源
- [Awesome Code Review](https://github.com/joho/awesome-code-review)
- [Clean Code concepts](https://github.com/ryanmcdermott/clean-code-javascript)
- [Code Quality Tools](https://github.com/collections/code-quality)

---

## 贡献指南

欢迎贡献改进建议！请遵循以下步骤：

1. Fork 本项目
2. 创建特性分支
3. 提交您的修改
4. 创建 Pull Request
5. 等待审查和合并

## 许可证

本模板基于 MIT 许可证发布，您可以自由使用和修改。