import { NextResponse } from "next/server";
import type { NextRequest } from "next/server";
import { createServerClient } from "@supabase/auth-helpers-nextjs";

export async function middleware(req: NextRequest) {
	const res = NextResponse.next();
	const supabase = createServerClient(
		process.env.NEXT_PUBLIC_SUPABASE_URL!,
		process.env.NEXT_PUBLIC_SUPABASE_ANON_KEY!,
		{
			cookies: {
				get(name: string) {
					return req.cookies.get(name)?.value;
				},
				set(name: string, value: string, options: any) {
					res.cookies.set({ name, value, ...options });
				},
				remove(name: string, options: any) {
					res.cookies.set({ name, value: "", ...options });
				},
			},
		},
	);

	const { data: { user } } = await supabase.auth.getUser();
	const url = req.nextUrl;
	const pathname = url.pathname;

	// Protect admin and employee sections
	const isAdminRoute = pathname.startsWith("/admin");
	const isEmployeeRoute = pathname.startsWith("/employee");

	if (!user && (isAdminRoute || isEmployeeRoute)) {
		url.pathname = "/login";
		return NextResponse.redirect(url);
	}

	if (user && (isAdminRoute || isEmployeeRoute)) {
		const { data: profile } = await supabase
			.from("users")
			.select("role")
			.eq("id", user.id)
			.single();

		const role = profile?.role as string | undefined;
		if (isAdminRoute && role !== "admin") {
			url.pathname = "/employee";
			return NextResponse.redirect(url);
		}
		if (isEmployeeRoute && role !== "employee" && role !== "admin") {
			url.pathname = "/admin";
			return NextResponse.redirect(url);
		}
	}

	return res;
}

export const config = {
	matcher: ["/admin/:path*", "/employee/:path*"],
};