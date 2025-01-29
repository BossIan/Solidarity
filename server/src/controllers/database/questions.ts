import { Request,  Response, NextFunction } from 'express';
import Questions from '../../models/Question'

const questions = async ( req: Request, res: Response, next: NextFunction) => {
    try {   
        const allQuestions = await Questions.find();
        if (!allQuestions || allQuestions.length === 0) {
            res.status(404).json({ message: 'No questions found' });
            return;
          }
          res.status(200).json({
            message: 'Successfully fetched all questions',
            data: allQuestions,
          });
    }
 catch (error) {
    next(error)
  }
}

export default questions
