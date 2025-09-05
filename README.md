# MyProject

A simple Python project template with a clean structure for development.

## 🚀 Features

- **Clean project structure** with `src/` layout
- **Comprehensive testing** with pytest
- **Type hints** and mypy support
- **Code formatting** with black and isort
- **Modern packaging** with both setup.py and pyproject.toml
- **Development tools** configuration
- **Example code** with documentation

## 📁 Project Structure

```
myproject/
├── src/
│   └── myproject/
│       ├── __init__.py      # Package initialization
│       ├── main.py          # Main application logic
│       └── utils.py         # Utility functions
├── tests/
│   ├── __init__.py
│   ├── test_main.py         # Tests for main module
│   └── test_utils.py        # Tests for utils module
├── docs/                    # Documentation
├── scripts/                 # Build/deployment scripts
├── requirements.txt         # Production dependencies
├── requirements-dev.txt     # Development dependencies
├── setup.py                 # Package setup (legacy)
├── pyproject.toml          # Modern package configuration
├── .gitignore              # Git ignore rules
└── README.md               # This file
```

## 🛠️ Installation

### Development Setup

1. Clone the repository:
   ```bash
   git clone <repository-url>
   cd myproject
   ```

2. Create a virtual environment (recommended):
   ```bash
   python3 -m venv venv
   source venv/bin/activate  # On Windows: venv\Scripts\activate
   ```

3. Install development dependencies:
   ```bash
   pip install -r requirements-dev.txt
   ```

4. Install the package in development mode:
   ```bash
   pip install -e .
   ```

## 🏃 Usage

### Running the Application

```bash
# Run directly
python -m src.myproject.main

# Or if installed
myproject
```

### Interactive Mode

```bash
python -m src.myproject.main --interactive
```

### Example Usage

```python
from myproject import greet, calculate, format_output

# Greet someone
message = greet("Developer")
print(message)  # Hello, Developer!

# Calculate sum
numbers = [1, 2, 3, 4, 5]
result = calculate(numbers)
print(result)  # 15

# Format output
formatted = format_output("Total", result)
print(formatted)  # Total: 15 (at 2025-09-05 08:09:12)
```

## 🧪 Testing

Run tests with pytest:

```bash
# Run all tests
pytest

# Run with coverage
pytest --cov=src/myproject

# Run specific test file
pytest tests/test_main.py

# Run with verbose output
pytest -v
```

## 🔧 Development Tools

### Code Formatting

```bash
# Format code with black
black src/ tests/

# Sort imports with isort
isort src/ tests/
```

### Type Checking

```bash
# Run mypy for type checking
mypy src/
```

### Linting

```bash
# Run flake8 for linting
flake8 src/ tests/
```

## 📦 Building and Distribution

```bash
# Build the package
python -m build

# Install from local build
pip install dist/myproject-0.1.0-py3-none-any.whl
```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Make your changes
4. Add tests for new functionality
5. Run the test suite (`pytest`)
6. Format your code (`black src/ tests/`)
7. Commit your changes (`git commit -m 'Add amazing feature'`)
8. Push to the branch (`git push origin feature/amazing-feature`)
9. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 🔗 Links

- **Documentation**: Coming soon
- **Issues**: [GitHub Issues](https://github.com/yourusername/myproject/issues)
- **PyPI**: Coming soon

## 📝 Changelog

### v0.1.0 (2025-09-05)
- Initial project structure
- Basic functionality implementation
- Comprehensive test suite
- Documentation and examples