package log;

import java.sql.Connection;
import java.sql.DriverManager;

public class javaPostgre {
	public static void main(String[] args) {
		Connection conn = null;
		try {
			Class.forName("org.postgresql.Driver");
			conn = DriverManager.getConnection("jdbc:postgresql://ec2-54-83-26-65.compute-1.amazonaws.com"
					+ ":5432/d9pf8qthde7brb", "gopasxxhdasfak", "ab14f9f8cbd407f8e7c7c99d3d03ac82f3c35b9d7a141615a563adeb2dd964f4");
			conn.close();
			System.out.println("OK");
		} catch (Exception e) {
			e.printStackTrace();
			System.out.println("G");
		}
	}
}