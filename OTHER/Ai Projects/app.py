from flask import Flask, request, jsonify
import openai
import random

app = Flask(__name__)

# Set your OpenAI key here
openai.api_key = "YOUR_OPENAI_API_KEY"

# Dummy job search function (replace with real API later)
def search_jobs(query):
    jobs = []
    for i in range(1,6):
        jobs.append({
            "title": f"{query} Job #{i}",
            "company": f"Company {random.choice(['A','B','C'])}",
            "location": "Ottawa, ON",
            "link": "https://www.example.com/job"
        })
    return jobs

@app.route('/api/jobs')
def api_jobs():
    query = request.args.get('query', '')
    return jsonify(search_jobs(query))

@app.route('/api/chat', methods=['POST'])
def api_chat():
    data = request.get_json()
    question = data.get('question', '')
    
    try:
        response = openai.ChatCompletion.create(
            model="gpt-4o-mini",
            messages=[{"role":"user","content":question}]
        )
        answer = response.choices[0].message.content
    except Exception as e:
        answer = f"Error: {e}"

    return jsonify({"answer": answer})

if __name__ == "__main__":
    app.run(debug=True)
