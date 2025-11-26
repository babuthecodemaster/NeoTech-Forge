const users = {
    _id: ObjectId, // Auto-generated unique ID
    name: String, // User's full name
    email: { type: String, unique: true, required: true }, // User's email
    password: String, // Hashed password
    createdAt: { type: Date, default: Date.now } // Timestamp
};
