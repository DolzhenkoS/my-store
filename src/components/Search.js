import React from 'react';

function Search({ value, onSetFilter }) {

    return (
        <div className='search'>

            <input onChange={(e) => { onSetFilter(e.target.value) }} type="text" placeholder='Найти...' value={value}></input>
        </div>

    )

}

export default Search;