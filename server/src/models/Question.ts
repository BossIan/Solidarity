import { type Document, model, Schema } from 'mongoose'
import { type Question } from '../@types'

interface I extends Document, Question {}

const instance = new Schema<I>(
  {

    question_id: {
      type: String,
      required: true,
      unique: true,
    },
    date_created: {
      type: String,
      required: true,
    },
    question: {
      type: String,
      required: true,
    },
    category: {
      type: String,
      required: true,
    },
    status: {
      type: String,
      required: true,
      enum: ['Active', 'Archived'],
      default: 'Active',
    },
  },
  {
    timestamps: true,
  }
)

// NOTE! use a singular model name, mongoose automatically creates a collection like so:
// model: 'Account' === collection: 'accounts'
const modelName = 'Question'

export default model<I>(modelName, instance)
