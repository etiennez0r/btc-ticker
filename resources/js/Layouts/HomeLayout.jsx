import NavBar from '@/Components/NavBar'
import React from 'react'

const HomeLayout = ({children}) => {
  return (
    <>
        <NavBar />

        <main className="flex items-center justify-center h-screen -mt-20">
            {children}
        </main>

    </>
  )
}

export default HomeLayout