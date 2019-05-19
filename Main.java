import java.sql.*;
import java.text.DateFormat;
import java.text.ParseException;
import java.text.SimpleDateFormat;

public class Main {
    private static Connection conn;

    public static void printTable(String tableName) throws SQLException {

        Statement stmt  = conn.createStatement();
        ResultSet res = stmt.executeQuery("SELECT * FROM " + tableName);
        ResultSetMetaData metaData = res.getMetaData();
        int columnsNumber = metaData.getColumnCount();
        System.out.format("%20s",tableName);
        System.out.println();
        for (int i = 1; i <= columnsNumber; i++)
        {
            System.out.format("%12s",metaData.getColumnLabel(i));
        }
        System.out.println();
        while (res.next()) {
            for(int i = 1 ; i <= columnsNumber; i++){
                System.out.format("%12s",res.getString(i));
            }

            System.out.println();
        }
    }
    public static void main(String[] args) {
        // JDBC driver name and database URL
        final String DB_URL = "jdbc:mysql://dijkstra.ug.bcc.bilkent.edu.tr:3306/orkun_elmas";
        //  Database credentials
        final String USER = "orkun.elmas";
        final String PASS = "hsfSIRv7";
        // Step 1: Allocate a database 'Connection' object
        try {
            conn = DriverManager.getConnection(DB_URL, USER, PASS);
            Statement stmt = conn.createStatement();
            // Step 2: Allocate a 'Statement' object in the Connection
            stmt = conn.createStatement();
            stmt.execute(
                    "CREATE TABLE IF NOT EXISTS Company("+
                            "CompanyID INT AUTO_INCREMENT,"+
                            "Name VARCHAR(255),"+
                            "Est_date DATE,"+
                            "Company_Info VARCHAR(255),"+
                            "Address_Street_Name VARCHAR(255),"+
                            "Address_Office_No VARCHAR(255),"+
                            "Address_Zipcode VARCHAR(255),"+
                            "Address_City VARCHAR(255),"+
                            "Address_Country VARCHAR(255),"+
                            "PRIMARY KEY(CompanyID)" +
                            ")ENGINE=InnoDB;"
            );
            stmt.execute(
                    "CREATE TABLE IF NOT EXISTS Account("+
                            "AccountID INT AUTO_INCREMENT,"+
                            "Email VARCHAR(255) NOT NULL,"+
                            "Username VARCHAR(255) NOT NULL,"+
                            "Password VARCHAR(255) NOT NULL,"+
                            "Name_first_name VARCHAR(255),"+
                            "Name_second_name VARCHAR(255),"+
                            "Birthdate DATE,"+
                            "PRIMARY KEY(AccountID),"+
                            "UNIQUE KEY(Username),"+
                            "UNIQUE KEY(Email)"+
                            ")ENGINE=InnoDB;"
            );
            stmt.execute(
                    "CREATE TABLE IF NOT EXISTS Comp_Rep("+
                            "AccountID INT,"+
                            "Branch VARCHAR(255),"+
                            "Contact_Info VARCHAR(255),"+
                            "Job_Title VARCHAR(255)," +
                            "PRIMARY KEY(AccountID),"+
                            "FOREIGN KEY(AccountID) REFERENCES Account(AccountID) ON DELETE CASCADE)ENGINE=InnoDB;"
            );
            stmt.execute(
                    "CREATE TABLE IF NOT EXISTS Post(" +
                            "PostID INT AUTO_INCREMENT," +
                            "CompanyID INT," +
                            "Title VARCHAR(255)," +
                            "Description VARCHAR(255)," +
                            "Creation_Date DATE," +
                            "Position VARCHAR(255)," +
                            "Job_Type VARCHAR(255)," +
                            "PRIMARY KEY(PostID)," +
                            "FOREIGN KEY(CompanyID) REFERENCES Company(CompanyID) ON DELETE CASCADE)ENGINE=InnoDB;"
            );
            stmt.execute(
                    "CREATE TABLE IF NOT EXISTS User(" +
                            "AccountID INT,"+
                            "PRIMARY KEY(AccountID)," +
                            "FOREIGN KEY(AccountID) REFERENCES Account(AccountID) ON DELETE CASCADE)" +
                            "ENGINE=InnoDB;"
            );
            stmt.execute("CREATE TABLE IF NOT EXISTS Employee(" +
                    "AccountID INT, " +
                    "Salary INT, " +
                    "Position VARCHAR(255), " +
                    "PRIMARY KEY(AccountID), " +
                    "FOREIGN KEY(AccountID) REFERENCES User(AccountID) ON DELETE CASCADE)ENGINE=InnoDB;");
            stmt.execute(
                    "CREATE TABLE IF NOT EXISTS Interviewee(" +
                            "AccountID INT,"+
                            "PRIMARY KEY(AccountID)," +
                            "FOREIGN KEY(AccountID) REFERENCES User(AccountID) ON DELETE CASCADE)ENGINE=InnoDB;"
            );
            stmt.execute("CREATE TABLE IF NOT EXISTS Interview_Review( " +
                    "PostID INT,"+
                    "Offered_salary INT, " +
                    "PRIMARY KEY(PostID)," +
                    "FOREIGN KEY(PostID) REFERENCES Review(PostID) ON DELETE CASCADE) " +
                    "ENGINE=InnoDB;");

            stmt.execute("CREATE TABLE IF NOT EXISTS Job_Review( " +
                    "PostID INT, " +
                    "Comments_Workplace VARCHAR(8191)," +
                    "Comments_Coworkers VARCHAR(8191)," +
                    "Comments_Management VARCHAR(8191)," +
                    "Position VARCHAR(255), " +
                    "Salary INT," +
                    "PRIMARY KEY(PostID),"+
                    "FOREIGN KEY(PostID) REFERENCES Review(PostID) ON DELETE CASCADE)ENGINE=InnoDB;");
            stmt.execute(
            "CREATE TABLE IF NOT EXISTS Post_Job_Review(" +
                    "PostID INT," +
                    "AccountID INT," +
                    "PRIMARY KEY(PostID)," +
                    "FOREIGN KEY(AccountID) REFERENCES Employee(AccountID) ON DELETE CASCADE," +
                    "FOREIGN KEY(PostID) REFERENCES Job_Review(PostID) ON DELETE CASCADE)ENGINE=InnoDB;");
            stmt.execute(
                    "CREATE TABLE IF NOT EXISTS Post_Interview_Review(" +
                            "PostID INT," +
                            "AccountID INT," +
                            "PRIMARY KEY(PostID)," +
                            "FOREIGN KEY(AccountID) REFERENCES Interviewee(AccountID) ON DELETE CASCADE," +
                            "FOREIGN KEY(PostID) REFERENCES Interview_Review (PostID)ON DELETE CASCADE)ENGINE=InnoDB;");
            /*stmt.execute(
                    "CREATE TABLE Review(" +
                            "PostID INT," +
                            "Anonimity BOOLEAN," +
                            "Rating FLOAT," +
                            "Pros VARCHAR(2047)," +
                            "Cons VARCHAR(2047)," +
                            "PRIMARY KEY(PostID)," +
                            "FOREIGN KEY(PostID) REFERENCES Post(PostID) ON DELETE CASCADE" +
                            ")ENGINE=InnoDB;"
            );*/

            /*
            insertValues();
            try{
                printTable("owns");
            }
            catch(SQLException e){
                System.err.println("could not print table owns");
            }
            try{
                printTable("account");
            }
            catch(SQLException e){
                System.err.println("could not print table account");
            }
            try{
                printTable("customer");
            }
            catch(SQLException e){
                System.err.println("could not print table customer");
            }*/
        } catch (SQLException e) {
            System.err.println("Error in creating tables");
            e.printStackTrace();
        }
        try {
            conn.close();
        } catch (SQLException e) {
            System.err.println("Could not close connection");
            e.printStackTrace();
        }
    }
}
