import { AppBar, IconButton, Popover } from '@mui/material'
import { useAuth } from 'contexts/AuthContext'
import React, {  MouseEventHandler, useState } from 'react'
import { useNavigate } from 'react-router-dom'
import 'styles/adminheader.css'
const AdminHeader = () => {
    const navigate = useNavigate();
    function handlelogout() {
    navigate('/');
    logout()
  }
    const { account , logout } = useAuth()
    const [popover, setPopover] = useState(false)
    const [anchorEl, setAnchorEl] = useState<(EventTarget & HTMLButtonElement) | null>(null)
  const openPopover: MouseEventHandler<HTMLButtonElement> = (e) => {
      setPopover(true)
      setAnchorEl(e.currentTarget)
    }
  
    const closePopover = () => {
      setPopover(false)
    }
    return (
      <AppBar className='header' elevation={0} position='relative'  sx={{ cursor:'pointer',backgroundColor: 'white',height: 60 }}>
        <div className="profile">
        <img className='schoolLogo' src="/logo.png" alt="" />
        <div className="text-profile">
        <p>{account?.username}</p>
        <p>Admin</p>
        </div>
        <IconButton   onClick={openPopover} >
        <img style={{width: '10px'}} className='more'src="/icons/more.png" alt="" />
        </IconButton>
        <Popover
        anchorEl={anchorEl}
        open={popover}
        onClose={closePopover}
        anchorOrigin={{ vertical: 'bottom', horizontal: 'right' }}
        transformOrigin={{ vertical: 'top', horizontal: 'right' }}
      >
            <p style={{cursor:'pointer'}} onClick={handlelogout}>Logout</p>

      </Popover>
        </div>
      </AppBar>
    )
  }
export default AdminHeader
