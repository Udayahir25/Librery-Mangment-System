"use client";

import { useState } from "react";
import { createClient } from "@/lib/supabase/client";
import { useRouter } from "next/navigation";

export default function LoginPage() {
	const [email, setEmail] = useState("");
	const [password, setPassword] = useState("");
	const [loading, setLoading] = useState(false);
	const [error, setError] = useState<string | null>(null);
	const router = useRouter();

	const onSubmit = async (e: React.FormEvent) => {
		e.preventDefault();
		setError(null);
		setLoading(true);
		try {
			const supabase = createClient();
			const { error } = await supabase.auth.signInWithPassword({ email, password });
			if (error) throw error;
			router.replace("/");
		} catch (err: any) {
			setError(err.message ?? "Login failed");
		} finally {
			setLoading(false);
		}
	};

	return (
		<main className="min-h-screen flex items-center justify-center p-6">
			<form onSubmit={onSubmit} className="w-full max-w-sm space-y-4">
				<h1 className="text-2xl font-semibold text-center">Login</h1>
				{error && <p className="text-red-600 text-sm">{error}</p>}
				<div className="space-y-2">
					<label className="block text-sm">Email</label>
					<input
						type="email"
						className="w-full rounded-md border px-3 py-2"
						value={email}
						onChange={(e) => setEmail(e.target.value)}
						required
					/>
				</div>
				<div className="space-y-2">
					<label className="block text-sm">Password</label>
					<input
						type="password"
						className="w-full rounded-md border px-3 py-2"
						value={password}
						onChange={(e) => setPassword(e.target.value)}
						required
					/>
				</div>
				<button
					type="submit"
					className="w-full rounded-md bg-black text-white py-2 disabled:opacity-50"
					disabled={loading}
				>
					{loading ? "Signing in..." : "Sign In"}
				</button>
			</form>
		</main>
	);
}