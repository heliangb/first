"""
Tests for the main module
"""

import pytest
import sys
from unittest.mock import patch
from src.myproject.main import greet, main


class TestGreet:
    """Test cases for the greet function."""
    
    def test_greet_default(self):
        """Test greeting with default name."""
        result = greet()
        assert result == "Hello, World!"
    
    def test_greet_custom_name(self):
        """Test greeting with custom name."""
        result = greet("Alice")
        assert result == "Hello, Alice!"
    
    def test_greet_empty_string(self):
        """Test greeting with empty string."""
        result = greet("")
        assert result == "Hello, !"


class TestMain:
    """Test cases for the main function."""
    
    @patch('builtins.input', return_value='TestUser')
    @patch('builtins.print')
    def test_main_interactive(self, mock_print, mock_input):
        """Test main function in interactive mode."""
        result = main(["--interactive"])
        assert result == 0
        mock_input.assert_called_once()
    
    @patch('builtins.print')
    def test_main_non_interactive(self, mock_print):
        """Test main function in non-interactive mode."""
        result = main(["--non-interactive"])
        assert result == 0
    
    @patch('builtins.print')
    def test_main_no_args(self, mock_print):
        """Test main function with no arguments."""
        with patch('builtins.input', side_effect=KeyboardInterrupt):
            result = main([])
            assert result == 0