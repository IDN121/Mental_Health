from flask import Flask, request, jsonify
import os
import pickle
import numpy as np

# Suppress TensorFlow logging
os.environ['TF_CPP_MIN_LOG_LEVEL'] = '3'

try:
    from tensorflow.keras.models import load_model
    from tensorflow.keras.preprocessing.sequence import pad_sequences
except ImportError:
    print("Error: TensorFlow tidak ditemukan. Jalankan perintah: pip install flask tensorflow pandas numpy scikit-learn")
    exit(1)

app = Flask(__name__)

BASE_DIR = os.path.dirname(os.path.abspath(__file__))
model_path = os.path.join(BASE_DIR, 'cnn_model.keras')
tokenizer_path = os.path.join(BASE_DIR, 'tokenizer.pickle')
labels_path = os.path.join(BASE_DIR, 'emotion_labels.pickle')

# Global variables for model and tokenizer
model = None
tokenizer = None
emotion_labels_array = None

def load_resources():
    global model, tokenizer, emotion_labels_array
    if not os.path.exists(model_path) or not os.path.exists(tokenizer_path):
        print("Model or tokenizer not found. Please run train_cnn.py first.")
        return False
        
    print("Loading CNN model into memory...")
    with open(tokenizer_path, 'rb') as handle:
        tokenizer = pickle.load(handle)
        
    with open(labels_path, 'rb') as handle:
        emotion_labels_array = pickle.load(handle)

    model = load_model(model_path)
    print("Model loaded successfully. Ready to serve requests!")
    return True

@app.route('/predict', methods=['POST'])
def predict():
    if model is None or tokenizer is None:
        return jsonify({"emotion": "Model Not Found", "confidence": 0})
        
    data = request.get_json()
    if not data or 'text' not in data:
        return jsonify({"error": "No text provided"}), 400
        
    text = data['text']
    
    # 1. Preprocessing
    MAX_SEQUENCE_LENGTH = 50 
    sequence = tokenizer.texts_to_sequences([text])
    X = pad_sequences(sequence, maxlen=MAX_SEQUENCE_LENGTH)

    # 2. Prediction
    predictions = model.predict(X, verbose=0)
    class_index = np.argmax(predictions[0])
    confidence = np.max(predictions[0]) * 100

    predicted_emotion = emotion_labels_array[class_index]
    
    # 3. Label mapping to english standard for Laravel
    label = str(predicted_emotion).lower()
    if label == 'senang': label = 'happy'
    if label == 'sedih': label = 'sad'
    if label == 'marah': label = 'stress'
    if label == 'cemas': label = 'stress'
    if label == 'takut': label = 'stress'
    if label == 'netral': label = 'neutral'

    return jsonify({
        "emotion": label,
        "confidence": round(float(confidence), 2)
    })

if __name__ == '__main__':
    if load_resources():
        app.run(host='127.0.0.1', port=5000, debug=False)
