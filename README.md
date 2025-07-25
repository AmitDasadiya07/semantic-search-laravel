# Laravel Semantic Search Assignment – Bluehole

## Description
This Laravel application allows users to perform semantic search on service categories using OpenAI embeddings. Users can enter a natural language query (e.g., “I want to upgrade flooring in my home”), and the system returns the most relevant category matches based on vector similarity.

## Features
- Import category data from Excel
- Generate and store OpenAI vector embeddings
- Perform semantic search using cosine similarity
- Blade-based UI for search input and result display
- Top 5 most relevant results shown with similarity scores

## Setup Instructions

### 1. Clone the Repository
```bash
git clone https://github.com/your-username/semantic-search-laravel.git
cd semantic-search-laravel

**Note:**  
For security reasons, I have removed my OpenAI API key from the `.env` file.  
**Please add your own OpenAI API key at the end of your `.env` file for the semantic search to work properly.**
