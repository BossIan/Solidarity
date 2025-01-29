import { Request,  Response, NextFunction } from 'express';
import Questions from '../../models/Question'

const addQuestion = async ( req: Request, res: Response, next: NextFunction) => {
    try {   
      const today = new Date();
      const formattedDate = today.toLocaleDateString('en-GB');
      
      const { category, question } = req.body
      const found = await Questions.findOne({ question })
      if (found) {
        return next({
          statusCode: 400,
          message: 'Question already exists',
        })
      }
      const lastItem = await Questions.findOne().sort({ question_id: -1 });
      var id;
      if (lastItem) {
        const newId = (parseInt(lastItem.question_id, 16) + 1).toString(16).padStart(6, '0');
        id = newId;
      } else {
        id = '000001';
      }
      const addedquestion = new Questions({ question_id: id,question, category, date_created: formattedDate})
    await addedquestion.save()
    console.log('Added ' + id);
      
      res.status(201).json({
        message: 'Succesfully added',
      })
       
    }
 catch (error) {
    next(error)
  }
}

export default addQuestion
