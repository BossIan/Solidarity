export interface Account {
  username: string
  password: string
  school?: string
  role: 'user' | 'admin'
}

export interface FormData {
  username: Account['username']
  password: Account['password']
  school?: Account['school']
}
export interface Question {
  question_id: string
  date_created: string
  question: string
  category: string
  status: 'Active' | 'Archived'
}