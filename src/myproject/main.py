"""
Main module for MyProject

Contains the main application logic and entry point.
"""

import sys
from typing import Optional
from .utils import calculate, format_output


def greet(name: str = "World") -> str:
    """
    Generate a greeting message.
    
    Args:
        name (str): The name to greet. Defaults to "World".
        
    Returns:
        str: A formatted greeting message.
    """
    return f"Hello, {name}!"


def main(args: Optional[list] = None) -> int:
    """
    Main entry point for the application.
    
    Args:
        args (Optional[list]): Command line arguments.
        
    Returns:
        int: Exit code (0 for success).
    """
    if args is None:
        args = sys.argv[1:]
    
    print("🐍 Welcome to MyProject!")
    print("=" * 30)
    
    # Example functionality
    greeting = greet("Developer")
    print(greeting)
    
    # Demo calculations
    numbers = [1, 2, 3, 4, 5]
    result = calculate(numbers)
    formatted_result = format_output("Sum of numbers", result)
    print(formatted_result)
    
    # Interactive example
    if not args or "--interactive" in args:
        try:
            user_name = input("\nWhat's your name? ")
            if user_name.strip():
                print(greet(user_name))
        except (KeyboardInterrupt, EOFError):
            print("\nGoodbye!")
    
    return 0


if __name__ == "__main__":
    sys.exit(main())