# class-timetable Specification

## Purpose

The class-timetable capability manages weekly period schedules for classes and sections, links teachers and subjects to classroom slots, and executes conflict detection to prevent teacher, room, and section collisions.

## Requirements

### Requirement: Timetable Slot Scheduling
The system MUST allow school administrators and academic deans to configure weekly schedule slots by day of week (`monday` through `sunday`), start time, end time, subject, teacher, and room.

#### Scenario: Adding a timetable slot
- **WHEN** a valid slot is submitted with no scheduling collisions
- **THEN** the system MUST persist the slot and display it within the weekly section schedule matrix.

### Requirement: Conflict Prevention Engine
The system MUST detect and prevent timetable conflicts across three dimensions: teacher double-booking, room double-booking, and section double-booking within the same time window.

#### Scenario: Teacher conflict detected
- **WHEN** a slot is created or edited assigning Teacher T at Day D from StartTime to EndTime, and Teacher T is already assigned to another class during an overlapping window
- **THEN** the system MUST reject the submission and return a descriptive conflict error specifying the conflicting class and section.

#### Scenario: Room conflict detected
- **WHEN** a slot specifies Room R at Day D from StartTime to EndTime, and Room R is already booked for another section during an overlapping window
- **THEN** the system MUST reject the submission and identify the conflicting booking.

#### Scenario: Section overlap detected
- **WHEN** a section already has a scheduled slot overlapping with the requested time window on the same day
- **THEN** the system MUST prevent the duplicate slot and report the collision.

### Requirement: Weekly Schedule Views
The system MUST provide a weekly matrix timetable view for sections and a schedule lookup view for individual teachers.

#### Scenario: Viewing section timetable
- **WHEN** a user selects an academic class and section
- **THEN** the system MUST render a structured weekly grid displaying all periods, subject names, assigned teachers, and room numbers.

#### Scenario: Viewing teacher personal timetable
- **WHEN** a user views a specific teacher's timetable
- **THEN** the system MUST render all teaching periods assigned to that teacher across all classes and sections.
