// routes/quiz.js
const express = require('express');
const router = express.Router();
const db = require('../db'); // Your DB connection

router.post('/create-template', async (req, res) => {
    const { title, timeLimit, questions } = req.body;

    try {
        // 1. Insert the Quiz Header
        const quizResult = await db.query(
            'INSERT INTO quizzes (title, time_limit) VALUES ($1, $2) RETURNING id',
            [title, timeLimit]
        );
        const quizId = quizResult.rows[0].id;

        // 2. Loop through questions and insert them
        const questionPromises = questions.map(q => {
            return db.query(
                'INSERT INTO questions (quiz_id, text, options, correct_answer) VALUES ($1, $2, $3, $4)',
                [quizId, q.text, JSON.stringify(q.options), q.correctAnswer]
            );
        });

        await Promise.all(questionPromises);
        res.status(201).send({ message: "Quiz Template Created Successfully!", quizId });
        
    } catch (err) {
        res.status(500).send({ error: "Database error" });
    }
});
