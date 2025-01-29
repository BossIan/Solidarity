import React from 'react'
import Header from 'components/Header'
import './styles/home.css'
import './styles/app.css'
import { useNavigate } from 'react-router-dom';
import Survey from 'components/survey';
interface Props {
  page: string;
}
const App: React.FC<Props>  = ({page}) => {
  var content;
  if (page === 'Home') {
    content = <Home />;  
  } else if (page === 'Survey') {
    content = <Survey />; 
  } else if (page === 'About us') {
    content = <AboutUs />; 
  }
  
  return (
    <div className='App'>
      <Header page={page} />
      {content}
    </div>
  )
}

const Home = () => {
  const navigate = useNavigate()
  return (
    <div className="home main">
      <h2>Solidarity</h2>
      <div className="content">
        <div className="text">
        <h1>
        Equity in Action, Unity in Progress.
        </h1>
        <svg width="507" height="34" viewBox="0 0 507 34" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M4 30C76.5163 10.3798 277.809 -17.0885 502.85 30" stroke="#7E3223" stroke-width="8" stroke-linecap="round"/>
        </svg>
        <p>Solidarity Scale is a platform dedicated to promoting fairness, unity, and collaboration. By providing tools and resources to measure and foster equitable practices, we empower individuals, communities, and organizations to work together for a more inclusive and sustainable future. Our mission is to turn solidarity into actionable progress, creating a balanced foundation for growth and harmony.</p>
        <div className="start-survey-button" onClick={() => navigate('Survey')}>
        Start Survey
        </div>
        </div>
      </div>

    </div>
  )
}


const AboutUs = () => {
  return (
    <div className="home main">
      <h2>Solidarity</h2>
    </div>
  )
}

export default App
