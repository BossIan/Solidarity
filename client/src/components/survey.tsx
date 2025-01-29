import React, { useEffect, useState } from "react"
import '../styles/survey.css'
import axios from 'utils/axios'
    var i = 0
    const Survey = () => {
    const [rows, setRows] = useState<any[]>([]);
    const categories = ["Category 1", "Category 2", "Category 3"];
    const [category, setCategory] = useState('Category 1');
    const updateChoice = (index: number, choice: number) => {
        setChoice((prev) => {
          const newArray = [...prev];
          newArray[index] = choice;
          return newArray;
        });
      };
      const [choices, setChoice] = useState<number[]>([]);
    const getQuestions = () => {
        return new Promise((resolve, reject) => {
          axios
            .post('/auth/questions')
            .then(({ data: { data: allQuestions } }) => {
              
            setRows(allQuestions)
            setChoice(new Array(rows.length).fill(0)); 
            console.log('w');
            
            resolve(true)
            })
            .catch((error) => {
              reject(error?.response?.data?.message || error.message)
            })
        })
      }
     useEffect(() => {
          getQuestions()
          .catch(error => {
            console.log(error);
        });
    }, []);

    return (
      <div className="survey main">
        <h2>Solidarity</h2>
        <p>Instructions: Thank you for participating in this survey on solidarity. Your responses will help us better understand the level of cohesion, support, and collective action within your community. Please read the instructions carefully before you begin.</p>
        <h1 className="surveyh1">{category}</h1>
        <div className="questions">
        {rows.map((row, index) => (
            <>
            {category == row.category? (
                <div className="question" key={index}>
                <span>{row.question}</span>
                <div className="choices">
                    <div className="choice">
                        <p>Strongly Disagree</p>
                        <svg onClick={()=>updateChoice(index, 1)} width="23" height="19" viewBox="0 0 23 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <ellipse cx="11.4899" cy="9.5" rx="10.656" ry="9.5" fill="#A2A2A2"/>
                        </svg>
                        {choices[index] === 1 ? (
                        <svg width="21"className="check" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18.4343 5.25432L19.0364 4.49466L18.2959 3.8692L16.7964 2.60273L16.0082 1.93706L15.3674 2.74555L7.85111 12.2288L5.50091 10.4092L4.6857 9.77807L4.0797 10.6122L2.91724 12.2122L2.34711 12.9969L3.11407 13.5907L7.73189 17.1658L8.51365 17.7711L9.12776 16.9963L18.4343 5.25432Z" stroke="#222222" stroke-width="2"/>
                        </svg>
                        
                        ) : (
                        <></>
                        )}
                    </div>
                    <div className="choice">
                        <p>Disagree</p>
                        <svg onClick={()=>updateChoice(index, 2)}  width="23" height="19" viewBox="0 0 23 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <ellipse cx="11.4899" cy="9.5" rx="10.656" ry="9.5" fill="#A2A2A2"/>
                        </svg>
                        {choices[index] === 2 ? (
                        <svg width="21"className="check" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18.4343 5.25432L19.0364 4.49466L18.2959 3.8692L16.7964 2.60273L16.0082 1.93706L15.3674 2.74555L7.85111 12.2288L5.50091 10.4092L4.6857 9.77807L4.0797 10.6122L2.91724 12.2122L2.34711 12.9969L3.11407 13.5907L7.73189 17.1658L8.51365 17.7711L9.12776 16.9963L18.4343 5.25432Z" stroke="#222222" stroke-width="2"/>
                        </svg>
                        
                        ) : (
                        <></>
                        )}
                    </div>
                    <div className="choice">
                        <p>Neutral</p>
                        <svg onClick={()=>updateChoice(index, 3)}  width="23" height="19" viewBox="0 0 23 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <ellipse cx="11.4899" cy="9.5" rx="10.656" ry="9.5" fill="#A2A2A2"/>
                        </svg>
                        {choices[index] === 3 ? (
                        <svg width="21"className="check" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18.4343 5.25432L19.0364 4.49466L18.2959 3.8692L16.7964 2.60273L16.0082 1.93706L15.3674 2.74555L7.85111 12.2288L5.50091 10.4092L4.6857 9.77807L4.0797 10.6122L2.91724 12.2122L2.34711 12.9969L3.11407 13.5907L7.73189 17.1658L8.51365 17.7711L9.12776 16.9963L18.4343 5.25432Z" stroke="#222222" stroke-width="2"/>
                        </svg>
                        
                        ) : (
                        <></>
                        )}
                    </div>
                    <div className="choice">
                        <p>Agree</p>
                        <svg onClick={()=>updateChoice(index, 4)}  width="23" height="19" viewBox="0 0 23 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <ellipse cx="11.4899" cy="9.5" rx="10.656" ry="9.5" fill="#A2A2A2"/>
                        </svg>
                        {choices[index] === 4 ? (
                        <svg width="21"className="check" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18.4343 5.25432L19.0364 4.49466L18.2959 3.8692L16.7964 2.60273L16.0082 1.93706L15.3674 2.74555L7.85111 12.2288L5.50091 10.4092L4.6857 9.77807L4.0797 10.6122L2.91724 12.2122L2.34711 12.9969L3.11407 13.5907L7.73189 17.1658L8.51365 17.7711L9.12776 16.9963L18.4343 5.25432Z" stroke="#222222" stroke-width="2"/>
                        </svg>
                        
                        ) : (
                        <></>
                        )}
                    </div>
                    <div className="choice">
                        <p>Strongly Agree</p>
                        <svg onClick={()=>updateChoice(index, 5)}  width="23" height="19" viewBox="0 0 23 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <ellipse cx="11.4899" cy="9.5" rx="10.656" ry="9.5" fill="#A2A2A2"/>
                        </svg>
                        {choices[index] === 5 ? (
                        <svg width="21"className="check" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18.4343 5.25432L19.0364 4.49466L18.2959 3.8692L16.7964 2.60273L16.0082 1.93706L15.3674 2.74555L7.85111 12.2288L5.50091 10.4092L4.6857 9.77807L4.0797 10.6122L2.91724 12.2122L2.34711 12.9969L3.11407 13.5907L7.73189 17.1658L8.51365 17.7711L9.12776 16.9963L18.4343 5.25432Z" stroke="#222222" stroke-width="2"/>
                        </svg>
                        
                        ) : (
                        <></>
                        )}
                    </div>
                    <div className="bar"></div>
                </div>
            </div>
            ): (
                <></>
            )}
            </>
        ))}
        </div>
        <div onClick={()=> {
            if (!categories[i]) {
                i = 0
            }
            setCategory(categories[i++])
            }} className="next">Next</div>
      </div>
    )
  }

export default Survey
