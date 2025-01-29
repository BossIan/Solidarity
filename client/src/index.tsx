import React from 'react'
import ReactDOM from 'react-dom/client'
import CssBaseline from '@mui/material/CssBaseline'
import { createBrowserRouter, Navigate, RouterProvider } from "react-router-dom";
import { AuthProvider } from 'contexts/AuthContext'
import App from 'App'
import Admin from 'Admin'
import 'styles/index.css'

const element = document.getElementById('root') as HTMLElement
const root = ReactDOM.createRoot(element)
const router = createBrowserRouter([{
    path: "/",
    element: <App page={'Home'} />,
  }, {
    path: "/survey",
    element: <App page={'Survey'} />,
  }, {
    path: "/about-us",
    element: <App page={'About us'} />,
  }, {
    path: "/admin",
    element: <Admin />,
  },{
    path: "*",
    element: <Navigate to="/" replace />, 
  }
  ]);
root.render(
  <React.StrictMode>
    <AuthProvider>
      <CssBaseline />
      <RouterProvider router={router}/>
    </AuthProvider>
  </React.StrictMode>
)
