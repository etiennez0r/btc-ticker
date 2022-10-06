import NavBar from '@/Components/NavBar'
import React from 'react'

const HomeLayout = ({children}) => {
  return (
    <>
        <NavBar />
        <main>{children}</main>
    </>
  )
}

export default HomeLayout