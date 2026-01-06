import './bootstrap';
import React from 'react';
import { createRoot } from 'react-dom/client';
import { Calendar20 } from './components/ui/calendar-with-time-presets';

// Check if we are on a page that needs the calendar
const calendarRoot = document.getElementById('calendar-booking-root');

if (calendarRoot) {
    const root = createRoot(calendarRoot);
    root.render(
        <React.StrictMode>
            <Calendar20 />
        </React.StrictMode>
    );
}
