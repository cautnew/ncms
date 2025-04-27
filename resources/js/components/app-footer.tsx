import { faCopyright } from '@fortawesome/free-regular-svg-icons';
import { faHeart } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import * as React from 'react';

interface AppContentProps extends React.ComponentProps<'footer'> {
    variant?: 'header' | 'sidebar';
}

export function AppFooter({ variant = 'header', children, ...props }: AppContentProps) {
    return (
        <footer className="w-full rounded-lg border-t-1 border-solid p-2 text-center font-sans text-sm font-medium text-gray-400" {...props}>
            <p>
                NCMS - Made with <FontAwesomeIcon className="mx-1 text-red-500" icon={faHeart} /> by{' '}
                <a href="mailto:felipedesmartins@gmail.com">Felipe Martins</a>
            </p>
            <p>
                <FontAwesomeIcon title="Copyright - All rights reserved" icon={faCopyright} /> - 2025
            </p>
        </footer>
    );
}
