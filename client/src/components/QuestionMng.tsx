import axios from 'utils/axios'
import React, {  useEffect, useRef, useState } from "react"
import 'styles/questionmng.css'
import { Question } from '@types';
var selected = "Category 1";

const QuestionMng = () => {
    const [rows, setRows] = useState<any[]>([]);
    
    const getQuestions = () => {
      return new Promise((resolve, reject) => {
        axios
          .post('/auth/questions')
          .then(({ data: { data: allQuestions } }) => {
            
            setRows(allQuestions)
            resolve(true)
          })
          .catch((error) => {
            reject(error?.response?.data?.message || error.message)
          })
      })
    }

    const [isOpen, setIsOpen] = useState(false);
    const categories = ["Category 1", "Category 2", "Category 3"];
    const dropdownRef = useRef<HTMLDivElement>(null);

    useEffect(() => {
      document.addEventListener("mousedown", (event: MouseEvent) => {
        if (dropdownRef.current && !dropdownRef.current.contains(event.target as Node)) {
          setIsOpen(false);
        }
      });
      getQuestions()
      .catch(error => {
        console.log(error);
      });
    }, []);
  const toggleDropdown = () => setIsOpen((prev) => !prev);
  const add = () => {
    const category = selected
    const question = document.querySelector('.username input') as HTMLInputElement
    if (question.value.replaceAll(' ', '') != '') {
      const data = {
        category: category,
        question: question.value
      }
        axios
        .post('/auth/add-question', data)
        .then(() => {
          getQuestions()
            .catch(error => {
              console.log(error);
            });
          console.log('Success');
        })
        .catch((error) => {
          console.log(error);
          alert(error.response.data.message)
          
        })
      }
    }
    const archive = (e: any) => {
        let data = []
      if (e == 'all') {
        const allChecked = document.querySelectorAll('td input:checked')
        if (allChecked.length == 0 ) {
          alert('Please select the questions you want to archive.');
          return
        }
        for (let i = 0; i < allChecked.length; i++) {
          data.push(allChecked[i].attributes[0].value)
        }
        
        axios
          .post('/auth/archive-question', data)
          .then(() => {
            getQuestions()
            .catch(error => {
              console.log(error);
            });
            console.log('Success');
          })
          .catch((error) => {
            console.log(error);
            alert(error.response.data.message)
          })
      } else {
        data.push(e.target.attributes[0].value)
          axios
          .post('/auth/archive-question', data)
          .then(() => {
            getQuestions()
            .catch(error => {
              console.log(error);
            });
            console.log('Success');
          })
          .catch((error) => {
            console.log(error);
            alert(error.response.data.message)
          })
      }

    }
    return (
   <div className="questionmng">
     <p style={{paddingTop:'20px', fontWeight:'bold', fontSize:'25px'}}>Question Management</p>
     <div className="category">
      <p>Category</p>
      <div ref={dropdownRef} className="selectNew" onClick={toggleDropdown}>
          <p>{selected}</p>
          {isOpen && (
            <ul >
              {categories.map((option, index) => (
                <li key={index} onClick={() => {
                  selected = option
                  console.log(isOpen);
                }}>
                  {option}
                </li>
              ))}
            </ul>
          )}
          <div  onClick={toggleDropdown} className={isOpen? "select active" : "select"}>
            <svg  onClick={toggleDropdown} id="Arrow - Down 2" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M19.25 8.5L12.25 15.5L5.25 8.5" stroke="#000000" stroke-width="1.5" stroke-linecap="square"></path>
              </svg>
      </div>
      </div>
     </div>
     <div className="username">
      <p>Question</p>
     <input type="text" placeholder="Enter Question"/>
     </div>
      < Table rows={rows} setRows={setRows} archive={archive}/>
        <div className="buttons">
          <div onClick={add} className="bottom-button">Add</div>
          <div onClick={()=>{
            archive('all')
          }} className="bottom-button">Archive</div>
          <div className="bottom-button">pdf</div>
        </div>
      </div>
    )
  
    
  }
interface table {
  rows: Question[];
  setRows: (rows: any) => void;
  archive: (e: any) => void;
}
const Table:React.FC<table>  = ({rows, setRows, archive}) => {
  const [toDateVal, setToDate] = useState<Date | null>(null);
  const [fromDateVal, setFromDate] = useState<Date | null>(null);
  const [statusOrder, setStatusOrder] = useState<"ascend" | "descend">("ascend");
  const [categoryOrder, setCategoryOrder] = useState<"ascend" | "descend">("ascend");
  

  const sortRowsByStatus = () => {
    const svg = document.querySelector('.statusDiv svg') as SVGElement;
    const sortedRows = [...rows].sort((a, b) => {
      if (statusOrder === "ascend") {
        svg.style.transform = 'rotate(180deg)';
        return a.status.localeCompare(b.status); 
      } else {
        svg.style.transform = 'rotate(0deg)';
        return b.status.localeCompare(a.status);
      }
    });
    setRows(sortedRows);
    setStatusOrder(statusOrder === "ascend" ? "descend" : "ascend"); 
  }

  const sortRowsByCategory = () => {
    const svg = document.querySelector('.categoryDiv svg') as SVGElement;
    const sortedRows = [...rows].sort((a, b) => {
      if (categoryOrder === "ascend") {
        svg.style.transform = 'rotate(180deg)';
        console.log(b.category);
        
        return a.category.localeCompare(b.category); 
      } else {
        svg.style.transform = 'rotate(0deg)';
        return b.category.localeCompare(a.category);
      }
    });
    setRows(sortedRows);
    setCategoryOrder(categoryOrder === "ascend" ? "descend" : "ascend"); 
  }
 const resetFilter = () => {
  const sortedRows = [...rows].sort((a, b) => {
    const svg = document.querySelectorAll('.sortDiv svg') as  NodeListOf<SVGElement>;
    for (let i = 0; i < svg.length; i++) {
      svg[i].style.transform = 'rotate(0deg)';
    }
      return a.question_id.localeCompare(b.question_id); 
  });
  setRows(sortedRows);
  setToDate(null)
  setFromDate(null)
 }
 const updateToDate = () => {
    const fromDate = document.getElementById("from") as HTMLInputElement | null;
    const date = fromDate?.value;
    setToDate(new Date(date as unknown as Date))
    
    const toDate = document.getElementById("to") as HTMLInputElement | null;
    if (fromDate && toDate) {
      const minToDate = new Date(date as unknown as Date);
      minToDate.setDate(minToDate.getDate() + 1);
      toDate.min = minToDate.toISOString().split("T")[0];
    }
  }
  const updateFromDate = () => {
    const toDate = document.getElementById("to")  as HTMLInputElement | null;
    const date = toDate?.value;
    const fromDate = document.getElementById("from") as HTMLInputElement | null;

    setFromDate(new Date(date as unknown as Date))
    if (fromDate && toDate) {
      const maxFromDate = new Date(date as unknown as Date);
      maxFromDate.setDate(maxFromDate.getDate() - 1); 
      fromDate.max = maxFromDate.toISOString().split("T")[0];
    }
  }
const checkAll = ( ) => {
  const svg =  document.querySelector("#tmp1 + label svg:last-child") as SVGElement
  const checkbox = document.querySelector("#tmp1") as HTMLInputElement
  const inputs = document.querySelectorAll('td input') 
  
  if (checkbox.checked) {
    svg.style.display = 'block';
    for (let i = 0; i < inputs.length; i++) {
      const input = inputs[i] as HTMLInputElement
      input.checked = true
    }
  } else {
    var allChecked = true
      for (let i = 0; i < inputs.length; i++) {
      const input = inputs[i] as HTMLInputElement
      if (!input.checked) {
        allChecked = false
      }
    }
    if (allChecked) {
      for (let i = 0; i < inputs.length; i++) {
        const input = inputs[i] as HTMLInputElement
        input.checked = false
      }
    }
    svg.style.display = 'none';
  }
}    
  return (
    <div className="table-container">
    <div className="filter-bar">
      <div className="sortDiv">
        <svg width="22" height="24" viewBox="0 0 22 24" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path fill-rule="evenodd" clip-rule="evenodd" d="M11.2959 9.75C16.7031 9.75 21.0865 7.73528 21.0865 5.25C21.0865 2.76472 16.7031 0.75 11.2959 0.75C5.88866 0.75 1.50525 2.76472 1.50525 5.25C1.50525 7.73528 5.88866 9.75 11.2959 9.75Z" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
      <path d="M1.50525 5.25C1.50779 9.76548 4.62433 13.688 9.0365 14.729V21C9.0365 22.2426 10.0481 23.25 11.2959 23.25C12.5437 23.25 13.5552 22.2426 13.5552 21V14.729C17.9674 13.688 21.084 9.76548 21.0865 5.25" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    
      </div>
      <div className="sortDiv">Filter By</div>
      <div className="sortDiv">
        <label htmlFor="from">From</label>
        <input id="from" type="date" value={toDateVal ? toDateVal.toISOString().split('T')[0] : ''} onChange={updateToDate} />
      </div>
      <div className="sortDiv">
        <label htmlFor="to">To</label>
        <input id="to" type="date" value={fromDateVal ? fromDateVal.toISOString().split('T')[0] : ''} onChange={updateFromDate} />
      </div>
      <div className="categoryDiv sortDiv"  onClick={sortRowsByCategory}>
        <p>Category</p>
        <svg id="Arrow - Down 2" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M19.25 8.5L12.25 15.5L5.25 8.5" stroke="#000000" stroke-width="1.5" stroke-linecap="square"></path>
        </svg>
      </div>
      <div className="statusDiv sortDiv"  onClick={sortRowsByStatus}>
        <p>Status</p>
        <svg id="Arrow - Down 2" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M19.25 8.5L12.25 15.5L5.25 8.5" stroke="#000000" stroke-width="1.5" stroke-linecap="square"></path>
        </svg>
      </div>
      <button className="reset-filter sortDiv" onClick={resetFilter}>
      <svg width="19" height="18" viewBox="0 0 19 18" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M9.15427 3.75V0.75L5.38865 4.5L9.15427 8.25V5.25C11.6471 5.25 13.673 7.2675 13.673 9.75C13.673 12.2325 11.6471 14.25 9.15427 14.25C6.66143 14.25 4.63552 12.2325 4.63552 9.75H3.12927C3.12927 13.065 5.82546 15.75 9.15427 15.75C12.4831 15.75 15.1793 13.065 15.1793 9.75C15.1793 6.435 12.4831 3.75 9.15427 3.75Z" fill="#EA0234"/>
</svg>
Reset Filter</button>
    </div>
    <div className="table">
    <table className="top-table">
      <thead>
        <tr>
          <th>
          <input id="tmp1" onChange={checkAll} type="checkbox" />
            <label  htmlFor="tmp1">
          <svg width="22" height="23" viewBox="0 0 22 23" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M8 21.5269H14C19 21.5269 21 19.5269 21 14.5269V8.52686C21 3.52686 19 1.52686 14 1.52686H8C3 1.52686 1 3.52686 1 8.52686V14.5269C1 19.5269 3 21.5269 8 21.5269Z" stroke="#ADA7A7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          <svg width="10" style={{display:'none', position:'absolute'}} height="3" viewBox="0 0 10 3" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M1 1.52686H9" stroke="#292D32" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
          </label>
          </th>
          <th>ID</th>
          <th>Date Created</th>
          <th>Question</th>
          <th>Category</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
    </table>
<div className="table-div">
      <table>
      <tbody>
      {rows.filter((row) => {
          const rowDate = new Date(row.date_created.split('/').reverse().join('-')); // Convert DD/MM/YYYY to YYYY-MM-DD
          if (fromDateVal && toDateVal) {
            const isAfterFromDate = rowDate <= fromDateVal;
            const isBeforeToDate = rowDate >= toDateVal;
          return isAfterFromDate && isBeforeToDate;
        }
        return true
          
        }).map((row, index) => (
          <tr key={index}>
            <td>
              <input onChange={() => {
                const inputs = document.querySelectorAll('td input') 
                var allChecked = true
                  for (let i = 0; i < inputs.length; i++) {
                  const input = inputs[i] as HTMLInputElement
                  if (!input.checked) {
                    allChecked = false
                  }
                }
                const svg =  document.querySelector("#tmp1 + label svg:last-child") as SVGElement
                const tmp1 =  document.querySelector("#tmp1") as HTMLInputElement
                if (allChecked) {
                  tmp1.checked = true
                  svg.style.display = 'block';
                } else {
                  tmp1.checked = false
                  svg.style.display = 'none';
                }
                
              }} id={row.question_id} type="checkbox" />
              <label htmlFor={row.question_id}>
                <svg width="22" height="23" viewBox="0 0 22 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M8 21.5269H14C19 21.5269 21 19.5269 21 14.5269V8.52686C21 3.52686 19 1.52686 14 1.52686H8C3 1.52686 1 3.52686 1 8.52686V14.5269C1 19.5269 3 21.5269 8 21.5269Z" stroke="#ADA7A7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <svg width="10" style={{display:'none'}} height="3" viewBox="0 0 10 3" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M1 1.52686H9" stroke="#292D32" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>

              </label>
            </td>
            <td>{'#' + row.question_id}</td>
            <td>{row.date_created}</td>
            <td>{row.question}</td>
            <td>{row.category}</td>
            <td>
              <div className="status" style={{
                  color: 
                        row.status === 'Active' ? '#B66A00' :
                        row.status === 'Archived' ? '#0A5774' : '#000', 
                  backgroundColor: 
                        row.status === 'Active' ? 'rgba(182, 106, 0, 0.2)' :
                        row.status === 'Archived' ? 'rgba(10, 87, 116, 0.2)' : '#000', 
                }}>
                <p>{row.status}</p>
              </div>
            </td>
            <td>
                <svg data-question-id={row.question_id}  onClick={archive} width="28" height="18" viewBox="0 0 28 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                 <path data-question-id={row.question_id} d="M14.0146 10.5C14.8136 10.5 15.5798 10.1839 16.1448 9.62132C16.7097 9.05871 17.0271 8.29565 17.0271 7.5C17.0271 6.70435 16.7097 5.94129 16.1448 5.37868C15.5798 4.81607 14.8136 4.5 14.0146 4.5C13.9676 4.5 13.9261 4.51125 13.8805 4.51359C14.0256 4.91171 14.0538 5.3428 13.9616 5.75628C13.8693 6.16976 13.6606 6.54847 13.3599 6.84797C13.0591 7.14746 12.6788 7.35532 12.2636 7.44715C11.8484 7.53898 11.4155 7.51097 11.0158 7.36641C11.0158 7.41328 11.0021 7.45453 11.0021 7.5C11.0021 7.89397 11.08 8.28407 11.2314 8.64805C11.3828 9.01203 11.6047 9.34274 11.8845 9.62132C12.4494 10.1839 13.2157 10.5 14.0146 10.5ZM27.4071 8.31562C24.8545 3.35578 19.801 0 14.0146 0C8.22826 0 3.17337 3.35813 0.622153 8.31609C0.514476 8.52821 0.458374 8.76256 0.458374 9.00023C0.458374 9.23791 0.514476 9.47226 0.622153 9.68438C3.17478 14.6442 8.22826 18 14.0146 18C19.801 18 24.8559 14.6419 27.4071 9.68391C27.5148 9.47179 27.5709 9.23744 27.5709 8.99977C27.5709 8.76209 27.5148 8.52774 27.4071 8.31562ZM14.0146 1.5C15.2063 1.5 16.3711 1.85189 17.3619 2.51118C18.3527 3.17047 19.125 4.10754 19.581 5.2039C20.037 6.30026 20.1563 7.50666 19.9239 8.67054C19.6914 9.83443 19.1176 10.9035 18.275 11.7426C17.4323 12.5818 16.3588 13.1532 15.19 13.3847C14.0213 13.6162 12.8099 13.4974 11.709 13.0433C10.608 12.5892 9.66705 11.8201 9.00501 10.8334C8.34297 9.84673 7.98961 8.68669 7.98961 7.5C7.99136 5.90923 8.62669 4.38412 9.75622 3.25928C10.8858 2.13444 12.4172 1.50174 14.0146 1.5ZM14.0146 16.5C8.96115 16.5 4.34354 13.6261 1.9646 9C3.30254 6.38433 5.4422 4.26095 8.0734 2.93766C7.09104 4.20422 6.48336 5.77359 6.48336 7.5C6.48336 9.48912 7.27683 11.3968 8.68922 12.8033C10.1016 14.2098 12.0172 15 14.0146 15C16.012 15 17.9276 14.2098 19.34 12.8033C20.7524 11.3968 21.5459 9.48912 21.5459 7.5C21.5459 5.77359 20.9382 4.20422 19.9559 2.93766C22.5871 4.26095 24.7267 6.38433 26.0646 9C23.6862 13.6261 19.0681 16.5 14.0146 16.5Z" fill="#B30C0C"/>
                </svg>
            </td>
          </tr>
        ))}
      </tbody>
      </table>
      </div>
    </div>
  </div>
  )
}
  export default QuestionMng
