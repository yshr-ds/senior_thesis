CREATE TABLE tasks (
    id SERIAL PRIMARY KEY,
    task_manager VARCHAR(255),
    task_name VARCHAR(255),
    task_detail TEXT,
    task_status VARCHAR(255),
    request_date DATE,
    start_date DATE,
    end_date DATE,
    task_level INTEGER
);