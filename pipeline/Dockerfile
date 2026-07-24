FROM python:3.11-slim

WORKDIR /app

# Prevent Python from writing .pyc files and buffer outputs for real-time logging
ENV PYTHONDONTWRITEBYTECODE=1 \
    PYTHONUNBUFFERED=1

# Install dependencies first (leverages Docker layer caching)
COPY requirements.txt .
RUN pip install --no-cache-dir --break-system-packages -r requirements.txt

# Copy pipeline source code and data folders
COPY . .

# Run the pipeline by default
# CMD ["python", "main.py"]
