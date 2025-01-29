import { type RequestHandler } from 'express'
import joi from '../../utils/joi'
import jwt from '../../utils/jwt'
import crypt from '../../utils/crypt'
import Account from '../../models/Account'

const register: RequestHandler = async (req, res, next) => {
    try {
    
    const validationError = await joi.validate(
      {
        username: joi.instance.string().required(),
        password: joi.instance.string().required(),
        school: joi.instance.string().optional().allow(''),
      },
      req.body
    )

    if (validationError) {
      
      return next(validationError)
    }

    const { username, password, school } = req.body

    // Verify account username as unique
    const found = await Account.findOne({ username })

    if (found) {
      return next({
        statusCode: 400,
        message: 'An account already exists with that "username"',
      })
    }

    // Encrypt password
    const hash = await crypt.hash(password)
    var account;
    // Create account
    
    if (school == '') {
     account = new Account({ username, password: hash})
    } else {
     account = new Account({ username, password: hash , school})
    }
    await account.save()

    // Generate access token
    const token = jwt.signToken({ uid: account._id, role: account.role })

    // Exclude password from response
    const { password: _, ...data } = account.toObject()

    res.status(201).json({
      message: 'Succesfully registered',
      data,
      token,
    })
  } catch (error) {
    console.log(error);
    
    next(error)
  }
}

export default register
