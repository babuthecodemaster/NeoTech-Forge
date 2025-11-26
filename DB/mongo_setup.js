const { MongoClient } = require("mongodb");

const uri = "mongodb://localhost:27017"; // Replace with your MongoDB connection string
const dbName = "neotech_forge";

async function setupDatabase() {
    const client = new MongoClient(uri);

    try {
        await client.connect();
        console.log("Connected to MongoDB");

        const db = client.db(dbName);

        // Create Users Collection
        await db.createCollection("users", {
            validator: {
                $jsonSchema: {
                    bsonType: "object",
                    required: ["email", "password"],
                    properties: {
                        name: { bsonType: "string" },
                        email: { bsonType: "string" },
                        password: { bsonType: "string" },
                        createdAt: { bsonType: "date" }
                    }
                }
            }
        });

        // Create Products Collection
        await db.createCollection("products", {
            validator: {
                $jsonSchema: {
                    bsonType: "object",
                    required: ["name", "price"],
                    properties: {
                        name: { bsonType: "string" },
                        description: { bsonType: "string" },
                        price: { bsonType: "double" },
                        imageUrl: { bsonType: "string" },
                        category: { bsonType: "string" },
                        createdAt: { bsonType: "date" }
                    }
                }
            }
        });

        // Create Orders Collection
        await db.createCollection("orders", {
            validator: {
                $jsonSchema: {
                    bsonType: "object",
                    required: ["userId", "totalPrice", "items"],
                    properties: {
                        userId: { bsonType: "objectId" },
                        totalPrice: { bsonType: "double" },
                        status: { bsonType: "string" },
                        createdAt: { bsonType: "date" },
                        items: {
                            bsonType: "array",
                            items: {
                                bsonType: "object",
                                required: ["productId", "quantity", "price"],
                                properties: {
                                    productId: { bsonType: "objectId" },
                                    quantity: { bsonType: "int" },
                                    price: { bsonType: "double" }
                                }
                            }
                        }
                    }
                }
            }
        });

        // Create Cart Collection
        await db.createCollection("cart", {
            validator: {
                $jsonSchema: {
                    bsonType: "object",
                    required: ["userId", "items"],
                    properties: {
                        userId: { bsonType: "objectId" },
                        items: {
                            bsonType: "array",
                            items: {
                                bsonType: "object",
                                required: ["productId", "quantity"],
                                properties: {
                                    productId: { bsonType: "objectId" },
                                    quantity: { bsonType: "int" }
                                }
                            }
                        },
                        updatedAt: { bsonType: "date" }
                    }
                }
            }
        });

        console.log("Database setup completed");
    } catch (err) {
        console.error("Error setting up database:", err);
    } finally {
        await client.close();
    }
}

setupDatabase();
