const express = require('express');
const mongoose = require('mongoose');
require('dotenv').config();

const app = express();
app.use(express.json());

const PORT = process.env.PORT || 3000;
const DB_HOST = process.env.MONGO_HOST || 'localhost';
const DB_PORT = process.env.MONGO_PORT || 27017;
const DB_NAME = process.env.MONGO_DB || 'auth';
const DB_USER = process.env.MONGO_USER || '';
const DB_PASS = process.env.MONGO_PASS || '';

const mongoURI = `mongodb://${DB_USER}:${DB_PASS}@${DB_HOST}:${DB_PORT}/${DB_NAME}?authSource=admin`;

// Подключение к MongoDB
mongoose.connect(mongoURI, {
  useNewUrlParser: true,
  useUnifiedTopology: true
})
.then(() => console.log('MongoDB connected'))
.catch(err => console.error('MongoDB connection error:', err));

// Модель пользователя
const UserSchema = new mongoose.Schema({
  username: { type: String, required: true, unique: true },
  password: { type: String, required: true }
});
const User = mongoose.model('User', UserSchema);

// Регистрация
app.post('/register', async (req, res) => {
  try {
    const { username, password } = req.body;
    const user = new User({ username, password });
    await user.save();
    res.status(201).json({ message: 'User created' });
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
});

// Проверка статуса
app.get('/status', (req, res) => {
  res.json({
    status: 'OK',
    db_connected: mongoose.connection.readyState === 1,
    environment: process.env.NODE_ENV || 'development'
  });
});

app.listen(PORT, () => {
  console.log(`Auth service running on port ${PORT}`);
  console.log(`Environment: ${process.env.NODE_ENV}`);
  console.log(`MongoDB: ${DB_HOST}:${DB_PORT}`);
});