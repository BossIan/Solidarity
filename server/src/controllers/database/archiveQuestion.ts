import { Request,  Response, NextFunction } from 'express';
import Questions from '../../models/Question'

const archiveQuestion = async ( req: Request, res: Response, next: NextFunction) => {
    try {  
    var inputs = req.body 
    for (let i = 0; i < inputs.length; i++) {
        const question = await Questions.findOne({ question_id: inputs[i] }); 
        if (question) {
        const newStatus = question.status === "Active" ? "Archived" : "Active";
        const result = await Questions.updateOne(
            { question_id: inputs[i] },
            { $set: { status:  newStatus} } 
          );
        }
    }       
    
  
      res.status(201).json({
        message: 'Succesfully archived',
      })
       
    }
 catch (error) {
    next(error)
  }
}

export default archiveQuestion
