"""
Utility functions for MyProject

Contains helper functions and common utilities.
"""

from typing import List, Union, Any
import datetime


def calculate(numbers: List[Union[int, float]]) -> float:
    """
    Calculate the sum of a list of numbers.
    
    Args:
        numbers (List[Union[int, float]]): List of numbers to sum.
        
    Returns:
        float: The sum of all numbers.
        
    Raises:
        ValueError: If the list is empty.
    """
    if not numbers:
        raise ValueError("Cannot calculate sum of empty list")
    
    return sum(numbers)


def format_output(label: str, value: Any, timestamp: bool = True) -> str:
    """
    Format output with a label and optional timestamp.
    
    Args:
        label (str): The label for the output.
        value (Any): The value to display.
        timestamp (bool): Whether to include a timestamp.
        
    Returns:
        str: Formatted output string.
    """
    output = f"{label}: {value}"
    
    if timestamp:
        now = datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S")
        output += f" (at {now})"
    
    return output


def validate_input(value: str, input_type: str = "string") -> bool:
    """
    Validate input based on type.
    
    Args:
        value (str): The input value to validate.
        input_type (str): The expected type ("string", "int", "float", "email").
        
    Returns:
        bool: True if valid, False otherwise.
    """
    if input_type == "string":
        return len(value.strip()) > 0
    elif input_type == "int":
        try:
            int(value)
            return True
        except ValueError:
            return False
    elif input_type == "float":
        try:
            float(value)
            return True
        except ValueError:
            return False
    elif input_type == "email":
        return "@" in value and "." in value
    
    return False