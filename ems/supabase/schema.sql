-- Users table (extends Supabase auth.users by profile info)
create table if not exists public.users (
	id uuid primary key references auth.users(id) on delete cascade,
	name text not null,
	email text not null unique,
	role text not null check (role in ('employee','admin')) default 'employee',
	department text,
	join_date date not null default current_date
);

-- Attendance
create table if not exists public.attendance (
	id bigserial primary key,
	user_id uuid not null references public.users(id) on delete cascade,
	date date not null,
	status text not null check (status in ('present','absent')),
	unique (user_id, date)
);

-- Salary
create table if not exists public.salary (
	id bigserial primary key,
	user_id uuid not null references public.users(id) on delete cascade,
	base_salary numeric(12,2) not null,
	bonus numeric(12,2) not null default 0,
	deductions numeric(12,2) not null default 0,
	net_salary numeric(12,2) generated always as (base_salary + bonus - deductions) stored,
	month date not null
);

-- Projects
create table if not exists public.projects (
	id bigserial primary key,
	name text not null,
	description text,
	progress integer not null default 0 check (progress between 0 and 100),
	status text not null default 'pending',
	start_date date not null default current_date,
	end_date date
);

-- Project assignments
create table if not exists public.project_assignments (
	id bigserial primary key,
	project_id bigint not null references public.projects(id) on delete cascade,
	user_id uuid not null references public.users(id) on delete cascade,
	unique (project_id, user_id)
);

-- Leave requests
create table if not exists public.leave_requests (
	id bigserial primary key,
	user_id uuid not null references public.users(id) on delete cascade,
	reason text not null,
	start_date date not null,
	end_date date not null,
	status text not null default 'pending' check (status in ('pending','approved','rejected'))
);

-- RLS
alter table public.users enable row level security;
alter table public.attendance enable row level security;
alter table public.salary enable row level security;
alter table public.projects enable row level security;
alter table public.project_assignments enable row level security;
alter table public.leave_requests enable row level security;

-- Policies: users
create policy "Users can view own profile" on public.users for select using (auth.uid() = id);
create policy "Admins can view all profiles" on public.users for select using (
	exists(select 1 from public.users u where u.id = auth.uid() and u.role = 'admin')
);
create policy "Admins can insert/update users" on public.users for all using (
	exists(select 1 from public.users u where u.id = auth.uid() and u.role = 'admin')
) with check (
	exists(select 1 from public.users u where u.id = auth.uid() and u.role = 'admin')
);

-- Policies: attendance
create policy "Employees view own attendance" on public.attendance for select using (
	auth.uid() = user_id or exists(select 1 from public.users u where u.id = auth.uid() and u.role = 'admin')
);
create policy "Admins manage attendance" on public.attendance for all using (
	exists(select 1 from public.users u where u.id = auth.uid() and u.role = 'admin')
) with check (
	exists(select 1 from public.users u where u.id = auth.uid() and u.role = 'admin')
);

-- Policies: salary
create policy "Employees view own salary" on public.salary for select using (
	auth.uid() = user_id or exists(select 1 from public.users u where u.id = auth.uid() and u.role = 'admin')
);
create policy "Admins manage salary" on public.salary for all using (
	exists(select 1 from public.users u where u.id = auth.uid() and u.role = 'admin')
) with check (
	exists(select 1 from public.users u where u.id = auth.uid() and u.role = 'admin')
);

-- Policies: projects
create policy "Employees and Admins read projects" on public.projects for select using (true);
create policy "Admins manage projects" on public.projects for all using (
	exists(select 1 from public.users u where u.id = auth.uid() and u.role = 'admin')
) with check (
	exists(select 1 from public.users u where u.id = auth.uid() and u.role = 'admin')
);

-- Policies: project_assignments
create policy "Users read related assignments" on public.project_assignments for select using (
	exists(select 1 from public.project_assignments pa where pa.user_id = auth.uid() and pa.id = id)
	or exists(select 1 from public.users u where u.id = auth.uid() and u.role = 'admin')
);
create policy "Admins manage assignments" on public.project_assignments for all using (
	exists(select 1 from public.users u where u.id = auth.uid() and u.role = 'admin')
) with check (
	exists(select 1 from public.users u where u.id = auth.uid() and u.role = 'admin')
);

-- Policies: leave_requests
create policy "Employees manage own leave requests" on public.leave_requests for all using (
	auth.uid() = user_id or exists(select 1 from public.users u where u.id = auth.uid() and u.role = 'admin')
) with check (
	auth.uid() = user_id or exists(select 1 from public.users u where u.id = auth.uid() and u.role = 'admin')
);