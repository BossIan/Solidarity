import express from 'express'
import checkBearerToken from '../middlewares/check-bearer-token'
import errorHandler from '../middlewares/error-handler'
import register from '../controllers/auth/register'
import login from '../controllers/auth/login'
import questions from '../controllers/database/questions'
import addQuestion from '../controllers/database/addQuestion'
import loginWithToken from '../controllers/auth/login-with-token'
import archiveQuestion from '../controllers/database/archiveQuestion'

const router = express.Router()

router.post('/register', [], register, errorHandler)

router.post('/login', [], login, errorHandler)


router.post('/questions', [], questions, errorHandler)
router.post('/add-question', [], addQuestion, errorHandler)

router.post('/archive-question', [], archiveQuestion, errorHandler)

router.get('/login', [checkBearerToken], loginWithToken, errorHandler)

export default router
