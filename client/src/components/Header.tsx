import React, { useEffect, useState } from 'react'
import { useAuth } from 'contexts/AuthContext'
import 'styles/header.css'
import { useNavigate } from 'react-router-dom';
import AuthModal from './AuthModal';


interface Props {
    page: string;
  
}

const Header: React.FC<Props> = ({page}) => {
  const { isLoggedIn, account } = useAuth()

  const navigate = useNavigate()
  useEffect(() => {
    let navElement: Element | null = null;

    if (page === 'Home') {
      navElement = document.querySelector('.nav:nth-child(1)');
    console.log(navElement);
    } else if (page === 'Survey') {
      navElement = document.querySelector('.nav:nth-child(2)');
    } else if (page === 'About us') {
      navElement = document.querySelector('.nav:nth-child(3)');
    }
    if (navElement) {
      navElement.classList.add('active');
    }
    return () => {
      navElement?.classList.remove('active');
    };
  }, [page]);
  const [modal, setModal] = useState('')



  return (
    <div className="header">
      <div className="ellipse"></div>
      <div className="navs">
        <div onClick={()=>navigate('/')} className="nav">Home</div>
        <div onClick={()=>navigate('/survey')} className="nav">Survey</div>
        <div onClick={()=>navigate('/about-us')} className="nav">About us</div>
        {isLoggedIn ? (
        <div className="nav">{account?.username}</div>

          ) : (
            <div className='nav'>
            <div className="login" onClick={()=>setModal('LOGIN')}>Login</div>
            <div className="signup" onClick={()=>setModal('REGISTER')}>Sign Up</div>
            </div>
            
          )}
      </div>
      <AuthModal modal={modal} setModal={setModal}/>
    </div>
  )
}

export default Header
