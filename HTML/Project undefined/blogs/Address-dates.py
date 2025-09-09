import os
from datetime import datetime, timedelta
from bs4 import BeautifulSoup

# Path to the blogs folder
blogs_folder = os.getcwd()  # Current folder (where script is)
index_file_path = os.path.join(os.path.dirname(blogs_folder), "index.php")

# Function to parse datetime string safely
def parse_datetime(dt_str):
    formats = ["%Y-%m-%d", "%Y-%m-%d %H:%M:%S", "%d-%m-%Y", "%d/%m/%Y"]
    for fmt in formats:
        try:
            return datetime.strptime(dt_str, fmt)
        except ValueError:
            continue
    return None

# Iterate over HTML files
for filename in os.listdir(blogs_folder):
    if filename.endswith(".html"):
        html_path = os.path.join(blogs_folder, filename)
        
        with open(html_path, "r", encoding="utf-8") as f:
            soup = BeautifulSoup(f, "html.parser")
        
        # Find <datetime> in body
        datetime_tag = soup.find("datetime")
        if datetime_tag:
            dt = parse_datetime(datetime_tag.get_text(strip=True))
            if dt and datetime.now() - dt > timedelta(days=30):
                
                # Get <h1> title
                h1_tag = soup.find("h1")
                if h1_tag:
                    title = h1_tag.get_text(strip=True)
                    
                    # Read index.php
                    with open(index_file_path, "r", encoding="utf-8") as f:
                        lines = f.readlines()
                    
                    # Remove lines containing the title
                    new_lines = [line for line in lines if title not in line]
                    
                    # Write back updated index.php
                    with open(index_file_path, "w", encoding="utf-8") as f:
                        f.writelines(new_lines)
                    
                    print(f"Removed lines containing '{title}' from index.php")
