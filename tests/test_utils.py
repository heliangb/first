"""
Tests for the utils module
"""

import pytest
from datetime import datetime
from src.myproject.utils import calculate, format_output, validate_input


class TestCalculate:
    """Test cases for the calculate function."""
    
    def test_calculate_positive_numbers(self):
        """Test calculation with positive numbers."""
        result = calculate([1, 2, 3, 4, 5])
        assert result == 15
    
    def test_calculate_mixed_numbers(self):
        """Test calculation with mixed positive and negative numbers."""
        result = calculate([1, -2, 3, -4, 5])
        assert result == 3
    
    def test_calculate_floats(self):
        """Test calculation with float numbers."""
        result = calculate([1.5, 2.5, 3.0])
        assert result == 7.0
    
    def test_calculate_single_number(self):
        """Test calculation with single number."""
        result = calculate([42])
        assert result == 42
    
    def test_calculate_empty_list(self):
        """Test calculation with empty list raises ValueError."""
        with pytest.raises(ValueError, match="Cannot calculate sum of empty list"):
            calculate([])


class TestFormatOutput:
    """Test cases for the format_output function."""
    
    def test_format_output_with_timestamp(self):
        """Test format output with timestamp."""
        result = format_output("Test", 123, timestamp=True)
        assert result.startswith("Test: 123 (at ")
        assert ")" in result
    
    def test_format_output_without_timestamp(self):
        """Test format output without timestamp."""
        result = format_output("Test", 123, timestamp=False)
        assert result == "Test: 123"
    
    def test_format_output_different_types(self):
        """Test format output with different value types."""
        result_str = format_output("String", "hello", timestamp=False)
        result_int = format_output("Integer", 42, timestamp=False)
        result_float = format_output("Float", 3.14, timestamp=False)
        
        assert result_str == "String: hello"
        assert result_int == "Integer: 42"
        assert result_float == "Float: 3.14"


class TestValidateInput:
    """Test cases for the validate_input function."""
    
    def test_validate_string_valid(self):
        """Test string validation with valid input."""
        assert validate_input("hello", "string") is True
        assert validate_input("  hello  ", "string") is True
    
    def test_validate_string_invalid(self):
        """Test string validation with invalid input."""
        assert validate_input("", "string") is False
        assert validate_input("   ", "string") is False
    
    def test_validate_int_valid(self):
        """Test integer validation with valid input."""
        assert validate_input("123", "int") is True
        assert validate_input("-456", "int") is True
        assert validate_input("0", "int") is True
    
    def test_validate_int_invalid(self):
        """Test integer validation with invalid input."""
        assert validate_input("abc", "int") is False
        assert validate_input("12.34", "int") is False
        assert validate_input("", "int") is False
    
    def test_validate_float_valid(self):
        """Test float validation with valid input."""
        assert validate_input("123.45", "float") is True
        assert validate_input("123", "float") is True
        assert validate_input("-456.78", "float") is True
    
    def test_validate_float_invalid(self):
        """Test float validation with invalid input."""
        assert validate_input("abc", "float") is False
        assert validate_input("", "float") is False
    
    def test_validate_email_valid(self):
        """Test email validation with valid input."""
        assert validate_input("test@example.com", "email") is True
        assert validate_input("user.name@domain.co.uk", "email") is True
    
    def test_validate_email_invalid(self):
        """Test email validation with invalid input."""
        assert validate_input("invalid-email", "email") is False
        assert validate_input("@domain.com", "email") is False
        assert validate_input("user@", "email") is False