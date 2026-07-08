import sys
import json
import os
import pickle
import numpy as np

# Suppress TensorFlow logging
os.environ['TF_CPP_MIN_LOG_LEVEL'] = '3'

try:
    from tensorflow.keras.models import load_model
    from tensorflow.keras.preprocessing.sequence import pad_sequences
except ImportError:
    # If TF is not installed, fallback to error message via JSON
    print(json.dumps({
        "emotion": "Error",
        "confidence": 0
    }))
    exit(1)

BASE_DIR = os.path.dirname(os.path.abspath(__file__))

model_path = os.path.join(BASE_DIR, 'cnn_model.keras')
tokenizer_path = os.path.join(BASE_DIR, 'tokenizer.pickle')
labels_path = os.path.join(BASE_DIR, 'emotion_labels.pickle')

# 1. Pastikan model dan tokenizer sudah ada
if not os.path.exists(model_path) or not os.path.exists(tokenizer_path):
    print(json.dumps({
        "emotion": "Model Not Found",
        "confidence": 0
    }))
    exit(1)

# 2. Load Model, Tokenizer, dan Label
with open(tokenizer_path, 'rb') as handle:
    tokenizer = pickle.load(handle)
    
with open(labels_path, 'rb') as handle:
    emotion_labels_array = pickle.load(handle)

model = load_model(model_path)

# 3. Ambil Teks dari PHP
if len(sys.argv) > 1:
    text = sys.argv[1]
else:
    text = ""

# 4. Preprocessing Teks (Tokenisasi & Padding)
MAX_SEQUENCE_LENGTH = 50 
sequence = tokenizer.texts_to_sequences([text])
X = pad_sequences(sequence, maxlen=MAX_SEQUENCE_LENGTH)

# 5. Prediksi dengan CNN
predictions = model.predict(X, verbose=0)
class_index = np.argmax(predictions[0])
confidence = np.max(predictions[0]) * 100

predicted_emotion = emotion_labels_array[class_index]

# Label mapping to english standard if needed
label = str(predicted_emotion).lower()
if label == 'senang': label = 'happy'
if label == 'sedih': label = 'sad'
if label == 'marah': label = 'stress'
if label == 'cemas': label = 'stress'
if label == 'takut': label = 'stress'
if label == 'netral': label = 'neutral'

# 6. Return ke Laravel dalam bentuk JSON
print(json.dumps({
    "emotion": label,
    "confidence": round(float(confidence), 2)
}))