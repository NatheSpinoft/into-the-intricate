import webbrowser
import os

# Example snippets
html_code = "<h1>Hello World</h1>"
css_code = "h1 { color: blue; }"
js_code = "console.log('Hello from JS');"

# Combine into one HTML file
full_html = f"""
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Preview</title>
<style>
{css_code}
</style>
</head>
<body>
{html_code}

<script>
{js_code}
</script>
</body>
</html>
"""

# Save to temporary file
file_path = "preview.html"
with open(file_path, "w", encoding="utf-8") as f:
    f.write(full_html)

# Open in default browser
webbrowser.open(f"file://{os.path.abspath(file_path)}")
