import HomeLayout from '@/Layouts/HomeLayout'
import axios from 'axios'
import React, { useEffect, useState } from 'react'
import {API_ENDPOINT, SYMBOL} from '@/utils'

const Home = () => {
  const [rows, setRows] = useState([])

  useEffect(() => {
    axios.get(`${API_ENDPOINT}/historical?symbol=${SYMBOL}`).then((result) => {
      setRows(result.data.rows)
    })
  }, [])

  return (
    <HomeLayout>
      <div className="content">
        <table class="w-full">
          <thead>
            <tr className="border border-gray-400">
              <th>ID</th>
              <th>Price</th>
              <th>Time</th>
            </tr>
          </thead>
          <tbody>
            {
              rows.map((row) => {
                return (<tr key={row.id}>
                  <td>{row.id}</td>
                  <td>{row.price}</td>
                  <td>{row.time}</td>
                </tr>)
              })
            }
          </tbody>
        </table>
      </div>
    </HomeLayout>
  )
}

export default Home