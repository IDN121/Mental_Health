import pandas as pd
import joblib

from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.naive_bayes import MultinomialNB

# membaca dataset
data = pd.read_csv("dataset.csv")

# memisahkan data
X = data["text"]
y = data["emotion"]

# ubah text menjadi angka
vectorizer = TfidfVectorizer()

X_vector = vectorizer.fit_transform(X)

# training model
model = MultinomialNB()

model.fit(X_vector, y)

# simpan model
joblib.dump(model, "model.pkl")
joblib.dump(vectorizer, "vectorizer.pkl")

print("Training selesai.")