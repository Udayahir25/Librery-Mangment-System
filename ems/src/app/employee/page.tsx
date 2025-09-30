import Link from "next/link";

export default async function EmployeeDashboardPage() {
	return (
		<main className="p-6 space-y-4">
			<h1 className="text-2xl font-semibold">Employee Dashboard</h1>
			<div className="grid gap-4 grid-cols-1 md:grid-cols-2">
				<Link className="underline" href="/employee/attendance">Attendance</Link>
				<Link className="underline" href="/employee/salary">Salary</Link>
				<Link className="underline" href="/employee/projects">Projects</Link>
				<Link className="underline" href="/employee/leaves">Leaves</Link>
			</div>
		</main>
	);
}