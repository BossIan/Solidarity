import React, { type ChangeEventHandler, Fragment, useEffect, useState } from 'react'
import { useAuth } from 'contexts/AuthContext'
import { Dialog, DialogTitle, TextField, Button, CircularProgress } from '@mui/material'
import { type FormData } from '@types'

interface Props {
  modal: string;
  setModal: (e: any)=> void
}

const AuthModal: React.FC<Props> = ({modal, setModal}) => {
  const { login, register } = useAuth()
  const isRegisterMode = modal === 'REGISTER'
  const isOpen = ['AUTH', 'LOGIN', 'REGISTER'].includes(modal)
  const onClose = () => {
    setModal('')
  }
  useEffect(() => {
    setModal(modal); 
  }, [modal]);
  const [isBulacanStudent, setIsBulacanStudent] = useState(true); 
  const [formData, setFormData] = useState<FormData>({ username: '', password: '', school: '' })
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState('')

  const handleChange: ChangeEventHandler<HTMLInputElement> = (e) => {
    const { name, value } = e.target
    setFormData((prev) => ({ ...prev, [name]: value }))
  }
  
  const handleCheckboxChange = () => {
    setIsBulacanStudent((prev) => !prev);
    setFormData((prev) => ({ ...prev, ['school']: '' }))

  };

  const clickSubmit = async () => {
    setLoading(true)
    setError('')

    try {
      isRegisterMode ? await register(formData) : await login(formData)
      onClose()
    } catch (error: any) {
      setError(typeof error === 'string' ? error : JSON.stringify(error))
    }

    setLoading(false)
  }

  var isSubmitButtonDisabled 
  if (isBulacanStudent) {
    isSubmitButtonDisabled = !formData['username'] || !formData['password']
  } else {
    isSubmitButtonDisabled = !formData['username'] || !formData['password'] || !formData['school']
  }

  return (
    <Dialog open={isOpen} onClose={onClose}>
      {isRegisterMode ? <DialogTitle>Create a new account</DialogTitle> : <DialogTitle>Login to your account</DialogTitle>}

      <TextField
        label='Username'
        name='username'
        type='text'
        value={formData['username']}
        onChange={handleChange}
        variant='filled'
        sx={{ mx: 2, my: 0.5 }}
        required
      />
      <TextField
        label='Password'
        name='password'
        type='password'
        value={formData['password']}
        onChange={handleChange}
        variant='filled'
        sx={{ mx: 2, my: 0.5 }}
        required
      />

      
      {error && <span className='error'>{error}</span>}

      {loading ? (
        <center>
          <CircularProgress color='inherit' />
        </center>
      ) : isRegisterMode ? (
        <>
          <div>
            <label>
              <input
                type="checkbox"
                checked={isBulacanStudent}
                onChange={handleCheckboxChange}
              />
              Are you a Bulacan State University student?
            </label>
          </div>  
            {!isBulacanStudent && (
                  <TextField
                    label='School'
                    name='school'
                    type="text"
                    value={formData['school']}
                    onChange={handleChange}
                    placeholder="Enter your school name"
                    variant='filled'
                    sx={{ mx: 2, my: 0.5 }}
                    required
                  />
            )}
          <Fragment>
            <Button onClick={clickSubmit} disabled={isSubmitButtonDisabled}>
              Register
            </Button>
            <Button onClick={() => setModal('LOGIN')}>I already have an account</Button>
          </Fragment>
        </>
      ) : (
        <Fragment>
          <Button onClick={clickSubmit} disabled={isSubmitButtonDisabled}>
            Login
          </Button>
          <Button onClick={() => setModal('REGISTER')}>I don't have an account</Button>
        </Fragment>
      )}
    </Dialog>
  )
}

export default AuthModal
