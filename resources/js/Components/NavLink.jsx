import React from 'react';
import { Link } from '@inertiajs/inertia-react';

export default function NavLink({ href, active, children }) {
    return (
        <Link
            href={href}
            className={
                active? 'active' : ''
            }
        >
            {children}
        </Link>
    );
}
