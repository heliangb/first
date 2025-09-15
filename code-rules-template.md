# 代码规则模板 (Code Rules Template)

## 概述 (Overview)

这是一个通用的代码规则模板，可以根据项目需求进行定制。

## 通用编程规范 (General Programming Standards)

### 命名规范 (Naming Conventions)

#### 变量命名 (Variable Naming)
```yaml
variables:
  style: camelCase  # JavaScript/TypeScript
  # style: snake_case  # Python
  # style: PascalCase  # C#
  descriptive: true
  abbreviations: avoid
  examples:
    good: ["userName", "totalAmount", "isLoggedIn"]
    bad: ["usr", "amt", "flag"]
```

#### 函数命名 (Function Naming)
```yaml
functions:
  style: camelCase
  prefix_verbs: true
  examples:
    good: ["getUserData", "calculateTotal", "validateInput"]
    bad: ["user", "total", "input"]
```

#### 类命名 (Class Naming)
```yaml
classes:
  style: PascalCase
  descriptive: true
  examples:
    good: ["UserManager", "DatabaseConnection", "PaymentProcessor"]
    bad: ["Manager", "Connection", "Processor"]
```

### 代码结构 (Code Structure)

#### 文件组织 (File Organization)
```yaml
file_structure:
  max_lines: 300
  single_responsibility: true
  imports:
    - group_by_type: true
    - sort_alphabetically: true
  exports:
    - explicit_exports: preferred
```

#### 函数规范 (Function Standards)
```yaml
functions:
  max_lines: 50
  max_parameters: 5
  single_responsibility: true
  return_type: explicit  # TypeScript
  documentation: required_for_public
```

### 注释规范 (Comment Standards)

```yaml
comments:
  style: "// 单行注释 (Single line)"
  block_style: "/* 多行注释 (Multi-line) */"
  documentation:
    - JSDoc: true  # JavaScript/TypeScript
    - docstring: true  # Python
  requirements:
    - explain_why_not_what: true
    - update_with_code: true
    - remove_obsolete: true
```

## 语言特定规则 (Language-Specific Rules)

### JavaScript/TypeScript

```yaml
javascript_typescript:
  formatting:
    semicolons: required
    quotes: single
    trailing_commas: true
    indentation: 2_spaces
  
  best_practices:
    - use_const_let: true
    - avoid_var: true
    - strict_mode: true
    - type_annotations: required  # TypeScript
    
  patterns:
    async_await: preferred_over_promises
    destructuring: use_when_appropriate
    template_literals: preferred_for_interpolation
```

### Python

```yaml
python:
  formatting:
    line_length: 88  # Black formatter
    indentation: 4_spaces
    quotes: double
    
  best_practices:
    - pep8_compliance: true
    - type_hints: required
    - docstrings: required_for_public
    - list_comprehensions: preferred_when_readable
    
  imports:
    - standard_library_first: true
    - third_party_second: true
    - local_imports_last: true
```

### Java

```yaml
java:
  formatting:
    indentation: 4_spaces
    braces: new_line
    line_length: 120
    
  naming:
    packages: lowercase
    classes: PascalCase
    methods: camelCase
    constants: UPPER_SNAKE_CASE
    
  best_practices:
    - access_modifiers: explicit
    - final_for_immutable: true
    - override_annotation: required
```

## 代码质量规则 (Code Quality Rules)

### 复杂度控制 (Complexity Control)

```yaml
complexity:
  cyclomatic_complexity: 10
  nesting_depth: 4
  function_length: 50
  class_length: 300
  file_length: 500
```

### 测试规范 (Testing Standards)

```yaml
testing:
  coverage_minimum: 80
  unit_tests: required
  integration_tests: recommended
  naming_convention: "test_should_[expected_behavior]_when_[condition]"
  
  structure:
    - arrange_act_assert: true
    - one_assertion_per_test: preferred
    - descriptive_test_names: required
```

### 错误处理 (Error Handling)

```yaml
error_handling:
  explicit_error_types: true
  catch_specific_exceptions: true
  logging: required
  user_friendly_messages: true
  
  patterns:
    - fail_fast: true
    - graceful_degradation: when_appropriate
    - retry_mechanisms: for_transient_failures
```

## 安全规范 (Security Standards)

```yaml
security:
  input_validation: always
  output_encoding: always
  sql_injection_prevention: parameterized_queries
  xss_prevention: escape_output
  authentication: strong_passwords_required
  authorization: principle_of_least_privilege
  
  sensitive_data:
    - no_hardcoded_secrets: true
    - environment_variables: for_config
    - encryption: for_sensitive_data
```

## 性能规范 (Performance Standards)

```yaml
performance:
  database:
    - use_indexes: true
    - avoid_n_plus_1: true
    - connection_pooling: true
    
  frontend:
    - lazy_loading: true
    - code_splitting: true
    - image_optimization: true
    
  caching:
    - appropriate_cache_levels: true
    - cache_invalidation_strategy: defined
```

## Git 工作流规范 (Git Workflow Standards)

```yaml
git:
  commit_messages:
    format: "type(scope): description"
    types: ["feat", "fix", "docs", "style", "refactor", "test", "chore"]
    max_length: 50
    
  branching:
    main_branch: "main"
    feature_branches: "feature/description"
    hotfix_branches: "hotfix/description"
    
  pull_requests:
    - code_review_required: true
    - tests_must_pass: true
    - description_required: true
```

## 文档规范 (Documentation Standards)

```yaml
documentation:
  readme:
    - installation_instructions: required
    - usage_examples: required
    - api_documentation: required
    - contributing_guidelines: recommended
    
  code_documentation:
    - public_apis: required
    - complex_algorithms: required
    - business_logic: recommended
    
  changelog:
    - version_updates: required
    - breaking_changes: highlighted
    - migration_guides: for_major_changes
```

## 工具配置 (Tool Configuration)

### Linting 配置 (Linting Configuration)

```yaml
linting:
  eslint: # JavaScript/TypeScript
    extends: ["@typescript-eslint/recommended", "prettier"]
    rules:
      no-unused-vars: error
      no-console: warn
      
  pylint: # Python
    disable: ["missing-docstring"]
    max-line-length: 88
    
  checkstyle: # Java
    checks: ["Indentation", "LineLength", "MethodLength"]
```

### 格式化配置 (Formatting Configuration)

```yaml
formatting:
  prettier: # JavaScript/TypeScript
    semi: true
    singleQuote: true
    trailingComma: "es5"
    
  black: # Python
    line-length: 88
    target-version: ["py38"]
    
  google-java-format: # Java
    style: "google"
```

## 项目特定规则 (Project-Specific Rules)

```yaml
project_specific:
  # 在这里添加项目特定的规则
  # Add project-specific rules here
  
  api_design:
    rest_conventions: true
    versioning_strategy: "semantic"
    error_response_format: "RFC 7807"
    
  database:
    naming_convention: "snake_case"
    migration_strategy: "forward_only"
    
  deployment:
    environment_parity: true
    blue_green_deployment: preferred
    rollback_strategy: defined
```

## 规则执行 (Rule Enforcement)

```yaml
enforcement:
  automated_checks:
    - pre_commit_hooks: true
    - ci_cd_pipeline: true
    - code_review_checklist: true
    
  tools:
    - linters: required
    - formatters: automated
    - security_scanners: recommended
    
  exceptions:
    - documented_reasons: required
    - team_approval: required
    - temporary_only: preferred
```

## 更新和维护 (Updates and Maintenance)

```yaml
maintenance:
  review_frequency: quarterly
  team_input: required
  version_control: this_document
  
  update_process:
    - propose_changes: via_pull_request
    - team_discussion: required
    - consensus: before_merge
    
  communication:
    - announce_changes: team_wide
    - training: for_major_changes
    - documentation: keep_updated
```

---

## 使用说明 (Usage Instructions)

1. **定制化**: 根据项目需求删除或修改不适用的规则
2. **工具集成**: 配置相应的 linting 和格式化工具
3. **团队协作**: 确保所有团队成员了解并同意这些规则
4. **持续改进**: 定期回顾和更新规则

## 参考资源 (References)

- [Google Style Guides](https://google.github.io/styleguide/)
- [Airbnb JavaScript Style Guide](https://github.com/airbnb/javascript)
- [PEP 8 -- Style Guide for Python Code](https://www.python.org/dev/peps/pep-0008/)
- [Clean Code by Robert C. Martin](https://www.amazon.com/Clean-Code-Handbook-Software-Craftsmanship/dp/0132350882)