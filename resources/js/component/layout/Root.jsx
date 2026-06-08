import React from 'react';
import Navbar from '../Header/Navbar';
import Footer from '../Footer/Footer';

const Root = ({ children }) => {
    return (
        <div>
            <Navbar></Navbar>
            {children}
            <Footer></Footer>
        </div>
    );
};

export default Root;
