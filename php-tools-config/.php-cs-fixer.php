<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/tests',
        __DIR__ . '/config',
        __DIR__ . '/database',
    ])
    ->exclude([
        'vendor',
        'storage',
        'bootstrap/cache',
        'node_modules',
    ])
    ->name('*.php')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

$config = new PhpCsFixer\Config();

return $config
    ->setRiskyAllowed(true)
    ->setRules([
        // PSR-12 标准
        '@PSR12' => true,
        
        // 数组语法
        'array_syntax' => ['syntax' => 'short'],
        'array_indentation' => true,
        'trim_array_spaces' => true,
        'trailing_comma_in_multiline' => [
            'elements' => ['arrays', 'arguments', 'parameters'],
        ],
        'whitespace_after_comma_in_array' => true,
        'no_whitespace_before_comma_in_array' => true,
        
        // 二元操作符
        'binary_operator_spaces' => [
            'default' => 'single_space',
            'operators' => [
                '=' => 'align_single_space_minimal',
                '=>' => 'align_single_space_minimal',
            ],
        ],
        'concat_space' => ['spacing' => 'one'],
        'operator_linebreak' => ['only_booleans' => true],
        
        // 大括号
        'braces' => [
            'allow_single_line_closure' => true,
            'position_after_functions_and_oop_constructs' => 'next',
            'position_after_control_structures' => 'same',
            'position_after_anonymous_constructs' => 'same',
        ],
        
        // 类和对象
        'class_attributes_separation' => [
            'elements' => [
                'method' => 'one',
                'property' => 'one',
                'trait_import' => 'none',
                'case' => 'none',
            ],
        ],
        'class_definition' => [
            'single_line' => true,
            'single_item_single_line' => true,
            'multi_line_extends_each_single_line' => true,
        ],
        'no_blank_lines_after_class_opening' => true,
        'no_blank_lines_after_phpdoc' => true,
        'single_class_element_per_statement' => true,
        'visibility_required' => [
            'elements' => ['property', 'method', 'const'],
        ],
        
        // 注释
        'single_line_comment_style' => [
            'comment_types' => ['hash'],
        ],
        'multiline_comment_opening_closing' => true,
        'no_empty_comment' => true,
        'comment_to_phpdoc' => [
            'ignored_tags' => ['todo', 'codeCoverageIgnore', 'phpcsSuppress'],
        ],
        
        // 控制结构
        'elseif' => true,
        'no_alternative_syntax' => true,
        'no_superfluous_elseif' => true,
        'no_unneeded_control_parentheses' => [
            'statements' => ['break', 'clone', 'continue', 'echo_print', 'return', 'switch_case', 'yield'],
        ],
        'no_useless_else' => true,
        'switch_case_semicolon_to_colon' => true,
        'switch_case_space' => true,
        'yoda_style' => [
            'equal' => false,
            'identical' => false,
            'less_and_greater' => false,
        ],
        
        // 函数和方法
        'function_declaration' => ['closure_function_spacing' => 'one'],
        'function_typehint_space' => true,
        'lambda_not_used_import' => true,
        'method_argument_space' => [
            'on_multiline' => 'ensure_fully_multiline',
            'keep_multiple_spaces_after_comma' => false,
        ],
        'no_spaces_after_function_name' => true,
        'return_type_declaration' => ['space_before' => 'none'],
        'single_line_throw' => false,
        
        // 导入
        'fully_qualified_strict_types' => true,
        'global_namespace_import' => [
            'import_classes' => true,
            'import_constants' => true,
            'import_functions' => true,
        ],
        'group_import' => true,
        'no_leading_import_slash' => true,
        'no_unused_imports' => true,
        'ordered_imports' => [
            'imports_order' => ['class', 'function', 'const'],
            'sort_algorithm' => 'alpha',
        ],
        'single_import_per_statement' => ['group_to_single_imports' => false],
        'single_line_after_imports' => true,
        
        // 语言结构
        'declare_equal_normalize' => ['space' => 'none'],
        'declare_strict_types' => true,
        'dir_constant' => true,
        'function_to_constant' => [
            'functions' => ['get_called_class', 'get_class', 'get_class_this', 'php_sapi_name', 'phpversion', 'pi'],
        ],
        'is_null' => true,
        'lowercase_cast' => true,
        'magic_constant_casing' => true,
        'magic_method_casing' => true,
        'modernize_types_casting' => true,
        'native_function_casing' => true,
        'no_alias_functions' => true,
        'no_mixed_echo_print' => ['use' => 'echo'],
        'no_short_bool_cast' => true,
        'no_unset_cast' => true,
        'normalize_index_brace' => true,
        'object_operator_without_whitespace' => true,
        'set_type_to_cast' => true,
        'short_scalar_cast' => true,
        'ternary_operator_spaces' => true,
        'ternary_to_null_coalescing' => true,
        'unary_operator_spaces' => true,
        
        // 命名空间
        'blank_line_after_namespace' => true,
        'clean_namespace' => true,
        'no_leading_namespace_whitespace' => true,
        'single_blank_line_before_namespace' => true,
        
        // 操作符
        'increment_style' => ['style' => 'post'],
        'logical_operators' => true,
        'new_with_braces' => true,
        'not_operator_with_successor_space' => true,
        'standardize_increment' => true,
        'standardize_not_equals' => true,
        
        // PHP标签
        'blank_line_after_opening_tag' => true,
        'echo_tag_syntax' => ['format' => 'long'],
        'full_opening_tag' => true,
        'linebreak_after_opening_tag' => true,
        'no_closing_tag' => true,
        
        // PHPDoc
        'align_multiline_comment' => ['comment_type' => 'phpdocs_only'],
        'phpdoc_add_missing_param_annotation' => ['only_untyped' => false],
        'phpdoc_align' => ['align' => 'vertical'],
        'phpdoc_annotation_without_dot' => true,
        'phpdoc_indent' => true,
        'phpdoc_inline_tag_normalizer' => true,
        'phpdoc_line_span' => [
            'const' => 'single',
            'method' => 'multi',
            'property' => 'single',
        ],
        'phpdoc_no_access' => true,
        'phpdoc_no_alias_tag' => true,
        'phpdoc_no_empty_return' => true,
        'phpdoc_no_package' => true,
        'phpdoc_no_useless_inheritdoc' => true,
        'phpdoc_order' => true,
        'phpdoc_order_by_value' => ['annotations' => ['covers']],
        'phpdoc_return_self_reference' => true,
        'phpdoc_scalar' => true,
        'phpdoc_separation' => true,
        'phpdoc_single_line_var_spacing' => true,
        'phpdoc_summary' => true,
        'phpdoc_tag_type' => ['tags' => ['inheritdoc' => 'inline']],
        'phpdoc_to_comment' => false,
        'phpdoc_trim' => true,
        'phpdoc_trim_consecutive_blank_line_separation' => true,
        'phpdoc_types' => true,
        'phpdoc_types_order' => [
            'null_adjustment' => 'always_last',
            'sort_algorithm' => 'alpha',
        ],
        'phpdoc_var_annotation_correct_order' => true,
        'phpdoc_var_without_name' => true,
        
        // 分号
        'multiline_whitespace_before_semicolons' => ['strategy' => 'no_multi_line'],
        'no_empty_statement' => true,
        'no_singleline_whitespace_before_semicolons' => true,
        'semicolon_after_instruction' => true,
        'space_after_semicolon' => ['remove_in_empty_for_expressions' => true],
        
        // 字符串
        'escape_implicit_backslashes' => [
            'double_quoted' => true,
            'heredoc_syntax' => true,
            'single_quoted' => false,
        ],
        'explicit_string_variable' => true,
        'heredoc_to_nowdoc' => true,
        'no_binary_string' => true,
        'simple_to_complex_string_variable' => true,
        'single_quote' => true,
        'string_line_ending' => true,
        
        // 空白
        'array_indentation' => true,
        'blank_line_before_statement' => [
            'statements' => ['break', 'continue', 'declare', 'return', 'throw', 'try'],
        ],
        'compact_nullable_typehint' => true,
        'heredoc_indentation' => ['indentation' => 'start_plus_one'],
        'indentation_type' => true,
        'line_ending' => true,
        'method_chaining_indentation' => true,
        'no_extra_blank_lines' => [
            'tokens' => [
                'break',
                'case',
                'continue',
                'curly_brace_block',
                'default',
                'extra',
                'parenthesis_brace_block',
                'return',
                'square_brace_block',
                'switch',
                'throw',
                'use',
            ],
        ],
        'no_spaces_around_offset' => ['positions' => ['inside', 'outside']],
        'no_spaces_inside_parenthesis' => true,
        'no_trailing_whitespace' => true,
        'no_trailing_whitespace_in_comment' => true,
        'no_whitespace_in_blank_line' => true,
        'single_blank_line_at_eof' => true,
        'types_spaces' => ['space' => 'none'],
        
        // 其他
        'cast_spaces' => ['space' => 'single'],
        'combine_consecutive_issets' => true,
        'combine_consecutive_unsets' => true,
        'explicit_indirect_variable' => true,
        'include' => true,
        'no_multiline_whitespace_around_double_arrow' => true,
        'no_short_echo_tag' => true,
        'no_trailing_comma_in_list_call' => true,
        'no_trailing_comma_in_singleline_array' => true,
        'normalize_index_brace' => true,
        'pow_to_exponentiation' => true,
        'psr_autoloading' => ['dir' => './src'],
        'random_api_migration' => true,
        'self_accessor' => true,
        'self_static_accessor' => true,
    ])
    ->setFinder($finder)
    ->setUsingCache(true)
    ->setCacheFile(__DIR__ . '/.php-cs-fixer.cache');