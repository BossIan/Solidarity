import React, { useEffect, useRef, useState } from 'react'
import { useAuth } from 'contexts/AuthContext'
import AdminHeader from 'components/AdminHeader'
import QuestionMng from 'components/QuestionMng'
import Sidebar from 'components/Sidebar'
import 'styles/admin.css'
import { useNavigate } from 'react-router-dom'

const Admin = () => {
  const { isLoggedIn  } = useAuth()
  const navigate = useNavigate();
  const hasMounted = useRef(false);
  const [activeTab, setActiveTab] = useState<string>('Dashboard');
  const [loading, setLoading] = useState(true);
  useEffect(() => {
    if (!hasMounted.current) {
      hasMounted.current = true;
      return; 
    } 

    if (loading) {
      setLoading(false)
      return
    }
    
    if ( !isLoggedIn ) {
      navigate('/', { replace: true });
    }
  }, [isLoggedIn]);
  const handleTabChange = (tabName: string) => {
    setActiveTab(tabName);
  };
  return (
    <div className='App'>
      <Dashboard activeTab={activeTab} onTabChange={handleTabChange} />
    </div>
  )

  
}

interface dashboardTab {
  activeTab: string;
  onTabChange: (tabName: string) => void;
}

const Dashboard: React.FC<dashboardTab>  =  ({ activeTab, onTabChange }) => {
  var content;
  if (activeTab == 'Dashboard') {
    content = <p>w</p>
  } else if (activeTab == 'Question Management') {
    content = QuestionMng()
  }
  return (
    <div className="dashboard">
      <Sidebar activeTab={activeTab} onTabChange={onTabChange} />
      <div className="mainTab">
      <AdminHeader />
      {content}
      </div>
    </div>
    
  )
}


export default Admin
