import React from 'react'
import NavLink from '@/Components/NavLink'

const NavBar = () => {
    
  return (
    <nav className="w-full pt-2 pl-2">
        <NavLink href={route('ticker')} active={route().current('ticker')}>Live ticker</NavLink>
        <NavLink href={route('historical')} active={route().current('historical')}>Historical</NavLink>
    </nav>
  )
}

export default NavBar