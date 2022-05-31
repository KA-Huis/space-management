import React from 'react'

export default function Dashboard({ user }) {
    return (
        <div>
            <h1>Hi, { user.full_name }</h1>
            <p>This is your dashboard.</p>
        </div>
    )
}
