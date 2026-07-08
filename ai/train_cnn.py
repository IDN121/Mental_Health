import os
import pickle
import numpy as np
import pandas as pd

# Suppress TensorFlow logging
os.environ['TF_CPP_MIN_LOG_LEVEL'] = '3'

try:
    from tensorflow.keras.models import Sequential
    from tensorflow.keras.layers import Embedding, Conv1D, GlobalMaxPooling1D, Dense, Dropout
    from tensorflow.keras.preprocessing.text import Tokenizer
    from tensorflow.keras.preprocessing.sequence import pad_sequences
    from sklearn.preprocessing import LabelEncoder
except ImportError:
    print("Error: TensorFlow tidak ditemukan. Jalankan perintah: pip install tensorflow scikit-learn")
    exit(1)

BASE_DIR = os.path.dirname(os.path.abspath(__file__))

print("Membaca dataset...")
data = pd.read_csv(os.path.join(BASE_DIR, "dataset.csv"))

X = data["text"].astype(str).tolist()
y = data["emotion"].tolist()

# 1. Label Encoding
print("Melakukan encoding pada label emosi...")
label_encoder = LabelEncoder()
y_encoded = label_encoder.fit_transform(y)
num_classes = len(np.unique(y_encoded))

emotion_labels = label_encoder.classes_
print(f"Kelas terdeteksi: {emotion_labels}")

with open(os.path.join(BASE_DIR, 'emotion_labels.pickle'), 'wb') as handle:
    pickle.dump(emotion_labels, handle, protocol=pickle.HIGHEST_PROTOCOL)

# 2. Tokenisasi Teks
print("Memproses tokenisasi teks...")
MAX_WORDS = 5000
MAX_SEQUENCE_LENGTH = 50

tokenizer = Tokenizer(num_words=MAX_WORDS, oov_token='<OOV>')
tokenizer.fit_on_texts(X)

sequences = tokenizer.texts_to_sequences(X)
X_pad = pad_sequences(sequences, maxlen=MAX_SEQUENCE_LENGTH)

with open(os.path.join(BASE_DIR, 'tokenizer.pickle'), 'wb') as handle:
    pickle.dump(tokenizer, handle, protocol=pickle.HIGHEST_PROTOCOL)

# 3. Membangun Arsitektur CNN
print("Membangun model CNN...")
model = Sequential()
model.add(Embedding(input_dim=MAX_WORDS, output_dim=100, input_length=MAX_SEQUENCE_LENGTH))
model.add(Conv1D(filters=128, kernel_size=3, activation='relu'))
model.add(GlobalMaxPooling1D())
model.add(Dense(64, activation='relu'))
model.add(Dropout(0.2))
model.add(Dense(num_classes, activation='softmax'))

model.compile(optimizer='adam', loss='sparse_categorical_crossentropy', metrics=['accuracy'])

# 4. Training Model
print("Memulai proses training...")
model.fit(X_pad, y_encoded, epochs=15, batch_size=8, verbose=1)

# 5. Simpan Model
model_path = os.path.join(BASE_DIR, "cnn_model.keras")
model.save(model_path)

print(f"\nTraining Selesai! Model disimpan di: {model_path}")
print("Tokenizer disimpan di: tokenizer.pickle")
print("Label emosi disimpan di: emotion_labels.pickle")
